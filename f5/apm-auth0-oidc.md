# F5 BIG-IP APM Auth0 OIDC Configuration Example

This is an implementation guide. Exact menu names can vary by BIG-IP version.

## Auth0 IdP

1. In Auth0, create a Regular Web Application.
2. Add the BIG-IP APM redirect URI to Allowed Callback URLs:

```text
https://login.example.com/oauth/client/redirect
```

3. Record the Auth0 domain, client ID, and client secret.

## BIG-IP APM Access Profile

1. Create an Access Profile for the HTTPS virtual server.
2. Add an OAuth Client / OpenID Connect logon item.
3. Configure Auth0 metadata:

```text
Issuer: https://your-tenant.us.auth0.com/
Discovery: https://your-tenant.us.auth0.com/.well-known/openid-configuration
Scopes: openid email profile
Response Type: code
```

4. Store ID token claims in session variables:

```text
session.oauth.client.last.id_token
session.oauth.client.last.id_token.email
session.oauth.client.last.id_token.name
session.oauth.client.last.id_token.sub
```

5. Add a Resource Assign or Allow ending.

## Header Forwarding

Forward these headers only after successful authentication:

```text
X-Authenticated-User: %{session.oauth.client.last.id_token.sub}
X-Authenticated-Email: %{session.oauth.client.last.id_token.email}
X-Authenticated-Name: %{session.oauth.client.last.id_token.name}
X-ID-Token: %{session.oauth.client.last.id_token}
X-Authenticated-Groups: %{session.ad.last.attr.memberOf}
```

The backend validates `X-ID-Token` when present. If you do not forward the ID token, keep the backend isolated so only F5 can reach it.

## Logout

Set `F5_LOGOUT_URL` to:

```text
https://login.example.com/my.logout.php3
```

The Flask logout route clears the app session and redirects to F5 APM logout.
