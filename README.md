# UnityFund

UnityFund is the merged fundraising platform with the OIDC login work from `login_page`.

The PHP app now uses Auth0/OpenID Connect for logon. The old EchoCare username/password login page and controller were removed, and `/index.php?page=login` now starts the OIDC Authorization Code Flow.

## Local Setup

```bash
cd UnityFund
cp .env.example .env
php -S 127.0.0.1:8000 -t public
```

Set these values in `UnityFund/.env` for direct Auth0 testing:

```env
APP_BASE_URL=http://127.0.0.1:8000
SESSION_COOKIE_SECURE=false
AUTH0_DOMAIN=dev-vq8bl6lh7ac0zurf.us.auth0.com
AUTH0_CLIENT_ID=your-auth0-client-id
AUTH0_CLIENT_SECRET=your-auth0-client-secret
OIDC_ROLE_CLAIM=https://unityfund.local/role
DEFAULT_USER_ROLE=fundraiser
```

Configure Auth0 with:

```text
Allowed Callback URLs: http://127.0.0.1:8000/index.php?page=auth_callback
Allowed Logout URLs: http://127.0.0.1:8000/index.php?page=logged_out
Allowed Web Origins: http://127.0.0.1:8000
```

The role claim must be one of: `user_admin`, `donee`, `fundraiser`, or `platform_manager`. If the claim is missing, UnityFund uses `DEFAULT_USER_ROLE`.
