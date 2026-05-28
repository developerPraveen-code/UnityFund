from flask import (
    Blueprint,
    current_app,
    redirect,
    render_template,
    request,
    session,
    url_for,
)
from typing import Optional

from .auth import (
    AuthError,
    build_auth0_logout_url,
    build_oidc_auth_url,
    create_authenticated_session,
    exchange_code_for_tokens,
    try_f5_header_auth,
    validate_id_token,
)

bp = Blueprint("main", __name__)


def current_user() -> Optional[dict]:
    return session.get("user") if session.get("authenticated") else None


def require_auth():
    # In production, F5 APM should block unauthenticated traffic before Flask.
    # This fallback creates an app session from trusted F5 identity headers.
    if not current_user():
        try:
            if try_f5_header_auth():
                return None
        except AuthError:
            session.clear()
            return render_template(
                "error.html",
                title="Invalid or expired token",
                message="The forwarded identity token could not be validated.",
            ), 401

    if not current_user():
        return redirect(url_for("main.login", next=request.path))
    return None


@bp.get("/")
def index():
    if current_user():
        return redirect(url_for("main.dashboard"))
    return redirect(url_for("main.login"))


@bp.get("/login")
def login():
    if current_user():
        return redirect(url_for("main.dashboard"))
    return render_template("login.html")


@bp.post("/login")
def start_login():
    if current_app.config["PREFER_F5_AUTH"] and current_app.config["F5_LOGOUT_URL"]:
        # With F5 APM, this route is usually never reached by anonymous users:
        # APM intercepts first and redirects to Auth0. For local fallback, use
        # direct Auth0 OIDC by setting PREFER_F5_AUTH=false.
        return redirect(url_for("main.dashboard"))

    try:
        return redirect(build_oidc_auth_url())
    except Exception:
        return render_template(
            "error.html",
            title="Login unavailable",
            message="Auth0 login could not be started. Check OIDC configuration.",
        ), 503


@bp.get("/auth/callback")
def auth_callback():
    if request.args.get("error"):
        return render_template(
            "error.html",
            title="Authentication failed",
            message=request.args.get("error_description") or request.args["error"],
        ), 401

    if request.args.get("state") != session.get("oidc_state"):
        session.clear()
        return render_template(
            "error.html",
            title="Invalid login state",
            message="The login response failed CSRF validation.",
        ), 400

    code = request.args.get("code")
    if not code:
        return render_template(
            "error.html",
            title="Missing authorization code",
            message="Auth0 did not return an authorization code.",
        ), 400

    try:
        tokens = exchange_code_for_tokens(code)
        claims = validate_id_token(tokens["id_token"])
        if claims.get("nonce") != session.get("oidc_nonce"):
            raise AuthError("The ID token nonce does not match this login attempt.")
        create_authenticated_session(claims, source="auth0-direct")
    except (AuthError, KeyError):
        session.clear()
        return render_template(
            "error.html",
            title="Invalid or expired token",
            message="Your Auth0 login token could not be validated. Please try again.",
        ), 401

    return redirect(url_for("main.dashboard"))


@bp.get("/dashboard")
def dashboard():
    redirect_response = require_auth()
    if redirect_response:
        return redirect_response
    return render_template("dashboard.html", user=current_user())


@bp.post("/logout")
def logout():
    session.clear()
    if current_app.config["PREFER_F5_AUTH"] and current_app.config["F5_LOGOUT_URL"]:
        return redirect(current_app.config["F5_LOGOUT_URL"])
    return redirect(build_auth0_logout_url())


@bp.app_errorhandler(404)
def not_found(_):
    return render_template(
        "error.html",
        title="Page not found",
        message="The page you requested does not exist.",
    ), 404


@bp.app_errorhandler(500)
def server_error(_):
    return render_template(
        "error.html",
        title="Server error",
        message="Something went wrong while processing your request.",
    ), 500
