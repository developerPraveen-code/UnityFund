import secrets
from datetime import datetime, timezone
from urllib.parse import urlencode

import jwt
import requests
from flask import current_app, session, url_for
from jwt import PyJWKClient
from jwt.exceptions import InvalidTokenError, PyJWKClientError

from .security import get_header, is_request_from_trusted_proxy


class AuthError(Exception):
    pass


def _oidc_metadata() -> dict:
    discovery_url = current_app.config["OIDC_DISCOVERY_URL"]
    if not discovery_url:
        raise AuthError("Auth0 OIDC discovery URL is not configured.")

    response = requests.get(discovery_url, timeout=5)
    response.raise_for_status()
    return response.json()


def build_oidc_auth_url() -> str:
    """Create an Auth0 Authorization Code Flow URL with CSRF state and nonce."""
    if (
        not current_app.config["AUTH0_CLIENT_ID"]
        or current_app.config["AUTH0_CLIENT_ID"].startswith("replace")
        or not current_app.config["AUTH0_CLIENT_SECRET"]
        or current_app.config["AUTH0_CLIENT_SECRET"].startswith("replace")
    ):
        raise AuthError("Auth0 OIDC client credentials are not configured.")

    metadata = _oidc_metadata()
    state = secrets.token_urlsafe(32)
    nonce = secrets.token_urlsafe(32)
    session["oidc_state"] = state
    session["oidc_nonce"] = nonce

    params = {
        "client_id": current_app.config["AUTH0_CLIENT_ID"],
        "redirect_uri": callback_url(),
        "response_type": "code",
        "scope": "openid email profile",
        "state": state,
        "nonce": nonce,
    }
    if current_app.config["AUTH0_AUDIENCE"]:
        params["audience"] = current_app.config["AUTH0_AUDIENCE"]

    return f"{metadata['authorization_endpoint']}?{urlencode(params)}"


def exchange_code_for_tokens(code: str) -> dict:
    metadata = _oidc_metadata()
    response = requests.post(
        metadata["token_endpoint"],
        data={
            "code": code,
            "client_id": current_app.config["AUTH0_CLIENT_ID"],
            "client_secret": current_app.config["AUTH0_CLIENT_SECRET"],
            "redirect_uri": callback_url(),
            "grant_type": "authorization_code",
        },
        timeout=5,
    )
    if response.status_code != 200:
        raise AuthError("Auth0 rejected the authorization code.")
    return response.json()


def callback_url() -> str:
    return f"{current_app.config['APP_BASE_URL']}{url_for('main.auth_callback')}"


def validate_id_token(raw_token: str) -> dict:
    metadata = _oidc_metadata()
    issuer = metadata["issuer"]
    jwks_client = PyJWKClient(metadata["jwks_uri"])

    try:
        signing_key = jwks_client.get_signing_key_from_jwt(raw_token)
        claims = jwt.decode(
            raw_token,
            signing_key.key,
            algorithms=["RS256"],
            audience=current_app.config["AUTH0_CLIENT_ID"],
            issuer=issuer,
        )
    except (InvalidTokenError, PyJWKClientError) as exc:
        raise AuthError("The ID token is expired or invalid.") from exc

    if not claims.get("sub"):
        raise AuthError("The ID token has no subject claim.")
    if claims.get("exp", 0) < datetime.now(timezone.utc).timestamp():
        raise AuthError("The ID token has expired.")
    return claims


def create_authenticated_session(claims: dict, source: str, groups: str = "") -> None:
    session.clear()
    session.permanent = True
    session["authenticated"] = True
    session["auth_source"] = source
    session["user"] = {
        "sub": claims.get("sub"),
        "email": claims.get("email", ""),
        "name": claims.get("name") or claims.get("email") or "Authenticated User",
        "picture": claims.get("picture", ""),
        "groups": groups,
    }


def try_f5_header_auth() -> bool:
    """Trust F5 identity headers only when the request comes from F5 CIDRs."""
    if not current_app.config["PREFER_F5_AUTH"] or not is_request_from_trusted_proxy():
        return False

    user = get_header(current_app.config["F5_AUTH_HEADER"])
    email = get_header(current_app.config["F5_EMAIL_HEADER"])
    name = get_header(current_app.config["F5_NAME_HEADER"])
    groups = get_header(current_app.config["F5_GROUPS_HEADER"])
    forwarded_id_token = get_header(current_app.config["F5_ID_TOKEN_HEADER"])

    if not user and not forwarded_id_token:
        return False

    if forwarded_id_token:
        claims = validate_id_token(forwarded_id_token)
    else:
        claims = {"sub": user, "email": email, "name": name or email or user}

    create_authenticated_session(claims, source="f5-apm", groups=groups)
    return True


def build_auth0_logout_url() -> str:
    if not current_app.config["AUTH0_DOMAIN"] or not current_app.config["AUTH0_CLIENT_ID"]:
        return url_for("main.login")

    params = {
        "client_id": current_app.config["AUTH0_CLIENT_ID"],
        "returnTo": f"{current_app.config['APP_BASE_URL']}{url_for('main.login')}",
    }
    return f"https://{current_app.config['AUTH0_DOMAIN']}/v2/logout?{urlencode(params)}"
