# Deployment Guide

## Security Model

F5 BIG-IP APM is the authentication enforcement point. It performs Auth0 OpenID Connect Authorization Code Flow, validates the Auth0 response, and forwards selected identity headers to Flask.

The Flask backend creates its own secure application session and trusts forwarded identity headers only when the immediate peer IP is inside `TRUSTED_PROXY_CIDRS`. Do not expose Flask directly to the internet in production.

## Required Environment Variables

Set these values in your process manager, container platform, or secret manager:

```bash
SECRET_KEY="generate-a-long-random-value"
APP_BASE_URL="https://login.example.com"
AUTH0_DOMAIN="your-tenant.us.auth0.com"
AUTH0_CLIENT_ID="your-auth0-client-id"
AUTH0_CLIENT_SECRET="your-auth0-client-secret"
AUTH0_AUDIENCE=""
TRUSTED_PROXY_CIDRS="10.10.1.20/32,10.10.1.21/32"
F5_LOGOUT_URL="https://login.example.com/my.logout.php3"
PREFER_F5_AUTH=true
```

## Gunicorn

```bash
gunicorn --bind 127.0.0.1:8000 --workers 3 --access-logfile - --error-logfile - wsgi:application
```

Terminate TLS at F5 and forward only from F5 to the backend. Preserve:

```text
X-Forwarded-Proto: https
X-Forwarded-Host: login.example.com
```

## Auth0 Application

Create an Auth0 Regular Web Application.

For F5 APM, add the redirect URI configured on BIG-IP APM to Allowed Callback URLs, typically:

```text
https://login.example.com/oauth/client/redirect
```

For direct Flask local testing with `PREFER_F5_AUTH=false`, add:

```text
Allowed Callback URLs: http://127.0.0.1:5000/auth/callback
Allowed Logout URLs: http://127.0.0.1:5000/login
Allowed Web Origins: http://127.0.0.1:5000
```

## Cookie Requirements

The app sets:

```text
Secure
HttpOnly
SameSite=Lax
```

Production traffic must use HTTPS or browsers will reject secure session cookies.

## Error Handling

Expired, malformed, replayed, or invalid ID tokens return a controlled error page and clear the session. Failed state or nonce checks are rejected.

## Hardening Checklist

- Restrict backend network access so only F5 can reach Flask.
- Set exact F5 self IPs in `TRUSTED_PROXY_CIDRS`.
- Strip inbound client-supplied identity headers at F5 before adding trusted ones.
- Use HTTPS-only virtual servers.
- Rotate `SECRET_KEY` using a controlled session invalidation process.
- Send logs to a centralized store without logging full tokens.
