# LOGIN_PAGE

Production-ready Flask application protected by F5 BIG-IP APM with Auth0 OpenID Connect.

The app supports the recommended production topology where F5 APM performs Auth0 OIDC Authorization Code Flow, validates the identity, and forwards authenticated identity headers to Flask. It also includes a direct Flask-managed Auth0 OIDC Authorization Code Flow for local testing or non-F5 environments.

## Project Structure

```text
LOGIN_PAGE/
  app/
    __init__.py
    auth.py
    config.py
    routes.py
    security.py
    static/css/styles.css
    templates/base.html
    templates/dashboard.html
    templates/error.html
    templates/login.html
  f5/
    apm-auth0-oidc.md
    irules.tcl
  docs/
    deployment.md
  .env.example
  requirements.txt
  run.py
  wsgi.py
```

## Quick Start

```bash
python3 -m venv .venv
source .venv/bin/activate
python -m pip install -r requirements.txt
cp .env.example .env
python -m flask --app run run --debug
```

For local direct Auth0 OIDC testing, set these values in `.env`:

```env
PREFER_F5_AUTH=false
APP_BASE_URL=http://127.0.0.1:5000
SESSION_COOKIE_SECURE=false
AUTH0_DOMAIN=dev-vq8bl6lh7ac0zurf.us.auth0.com
AUTH0_CLIENT_ID=your-auth0-client-id
AUTH0_CLIENT_SECRET=your-auth0-client-secret
```

In Auth0, configure:

```text
Allowed Callback URLs: http://127.0.0.1:5000/auth/callback
Allowed Logout URLs: http://127.0.0.1:5000/login
Allowed Web Origins: http://127.0.0.1:5000
Application Login URI: leave blank for local HTTP testing
```

Production deployments should run behind HTTPS and F5 BIG-IP APM. See [docs/deployment.md](docs/deployment.md) and [f5/apm-auth0-oidc.md](f5/apm-auth0-oidc.md).
