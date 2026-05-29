import os
from datetime import timedelta

from dotenv import load_dotenv

load_dotenv()


def _bool(name: str, default: bool) -> bool:
    value = os.getenv(name)
    if value is None:
        return default
    return value.strip().lower() in {"1", "true", "yes", "on"}


class Config:
    SECRET_KEY = os.getenv("SECRET_KEY", "dev-only-change-me")
    SESSION_COOKIE_NAME = os.getenv("SESSION_COOKIE_NAME", "unityfund_session")
    SESSION_COOKIE_HTTPONLY = True
    SESSION_COOKIE_SECURE = _bool("SESSION_COOKIE_SECURE", True)
    SESSION_COOKIE_SAMESITE = "Lax"
    PERMANENT_SESSION_LIFETIME = timedelta(
        minutes=int(os.getenv("SESSION_LIFETIME_MINUTES", "60"))
    )

    APP_BASE_URL = os.getenv("APP_BASE_URL", "https://localhost:5000").rstrip("/")

    AUTH0_DOMAIN = os.getenv("AUTH0_DOMAIN", "").strip().rstrip("/")
    AUTH0_CLIENT_ID = os.getenv("AUTH0_CLIENT_ID", "")
    AUTH0_CLIENT_SECRET = os.getenv("AUTH0_CLIENT_SECRET", "")
    AUTH0_AUDIENCE = os.getenv("AUTH0_AUDIENCE", "")
    OIDC_DISCOVERY_URL = os.getenv("OIDC_DISCOVERY_URL", "")
    if not OIDC_DISCOVERY_URL and AUTH0_DOMAIN:
        OIDC_DISCOVERY_URL = f"https://{AUTH0_DOMAIN}/.well-known/openid-configuration"

    TRUSTED_PROXY_CIDRS = [
        item.strip()
        for item in os.getenv("TRUSTED_PROXY_CIDRS", "").split(",")
        if item.strip()
    ]
    F5_AUTH_HEADER = os.getenv("F5_AUTH_HEADER", "X-Authenticated-User")
    F5_EMAIL_HEADER = os.getenv("F5_EMAIL_HEADER", "X-Authenticated-Email")
    F5_NAME_HEADER = os.getenv("F5_NAME_HEADER", "X-Authenticated-Name")
    F5_GROUPS_HEADER = os.getenv("F5_GROUPS_HEADER", "X-Authenticated-Groups")
    F5_ID_TOKEN_HEADER = os.getenv("F5_ID_TOKEN_HEADER", "X-ID-Token")
    F5_LOGOUT_URL = os.getenv("F5_LOGOUT_URL", "")
    PREFER_F5_AUTH = _bool("PREFER_F5_AUTH", True)
