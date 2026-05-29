<?php

require_once __DIR__ . '/AuthConfig.php';

class OidcClient
{
    public function authorizationUrl(): string
    {
        $this->assertConfigured();

        $metadata = $this->metadata();
        $state = bin2hex(random_bytes(32));
        $nonce = bin2hex(random_bytes(32));

        $_SESSION['oidc_state'] = $state;
        $_SESSION['oidc_nonce'] = $nonce;

        $params = [
            'client_id' => AuthConfig::get('AUTH0_CLIENT_ID'),
            'redirect_uri' => $this->callbackUrl(),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'nonce' => $nonce,
            'prompt' => 'login',
        ];

        $audience = AuthConfig::get('AUTH0_AUDIENCE');
        if ($audience !== '') {
            $params['audience'] = $audience;
        }

        return $metadata['authorization_endpoint'] . '?' . http_build_query($params);
    }

    public function authenticateCallback(array $query): array
    {
        if (($query['error'] ?? '') !== '') {
            throw new RuntimeException($query['error_description'] ?? $query['error']);
        }

        if (($query['state'] ?? '') === '' || $query['state'] !== ($_SESSION['oidc_state'] ?? null)) {
            throw new RuntimeException('The login response failed state validation.');
        }

        $code = $query['code'] ?? '';
        if ($code === '') {
            throw new RuntimeException('The identity provider did not return an authorization code.');
        }

        $tokens = $this->exchangeCode($code);
        $claims = $this->validateIdToken($tokens['id_token'] ?? '');

        if (($claims['nonce'] ?? '') !== ($_SESSION['oidc_nonce'] ?? null)) {
            throw new RuntimeException('The ID token nonce does not match this login attempt.');
        }

        unset($_SESSION['oidc_state'], $_SESSION['oidc_nonce']);

        return $claims;
    }

    public function logoutUrl(): string
    {
        $domain = rtrim(AuthConfig::get('AUTH0_DOMAIN'), '/');
        $clientId = AuthConfig::get('AUTH0_CLIENT_ID');

        if ($domain === '' || $clientId === '') {
            return '/index.php?page=logged_out';
        }

        return 'https://' . $domain . '/v2/logout?' . http_build_query([
            'client_id' => $clientId,
            'returnTo' => AuthConfig::baseUrl() . '/index.php?page=logged_out',
        ]);
    }

    private function exchangeCode(string $code): array
    {
        $metadata = $this->metadata();
        $response = $this->httpPost($metadata['token_endpoint'], [
            'code' => $code,
            'client_id' => AuthConfig::get('AUTH0_CLIENT_ID'),
            'client_secret' => AuthConfig::get('AUTH0_CLIENT_SECRET'),
            'redirect_uri' => $this->callbackUrl(),
            'grant_type' => 'authorization_code',
        ]);

        return $response;
    }

    private function validateIdToken(string $rawToken): array
    {
        if ($rawToken === '') {
            throw new RuntimeException('Missing ID token.');
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $this->splitJwt($rawToken);
        $header = $this->jsonDecode($this->base64UrlDecode($encodedHeader));
        $claims = $this->jsonDecode($this->base64UrlDecode($encodedPayload));

        if (($header['alg'] ?? '') !== 'RS256') {
            throw new RuntimeException('Unsupported ID token signature algorithm.');
        }

        $metadata = $this->metadata();
        $jwk = $this->matchingJwk($metadata['jwks_uri'], $header['kid'] ?? '');
        $publicKey = $this->jwkToPem($jwk);

        $signedContent = $encodedHeader . '.' . $encodedPayload;
        $signature = $this->base64UrlDecode($encodedSignature);
        $verified = openssl_verify($signedContent, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        if ($verified !== 1) {
            throw new RuntimeException('The ID token signature is invalid.');
        }

        $issuer = $metadata['issuer'] ?? '';
        if (($claims['iss'] ?? '') !== $issuer) {
            throw new RuntimeException('The ID token issuer is invalid.');
        }

        $audience = $claims['aud'] ?? '';
        $clientId = AuthConfig::get('AUTH0_CLIENT_ID');
        if (is_array($audience) && !in_array($clientId, $audience, true)) {
            throw new RuntimeException('The ID token audience is invalid.');
        }

        if (is_string($audience) && $audience !== $clientId) {
            throw new RuntimeException('The ID token audience is invalid.');
        }

        if (!is_string($audience) && !is_array($audience)) {
            throw new RuntimeException('The ID token audience is invalid.');
        }

        if (($claims['exp'] ?? 0) < time()) {
            throw new RuntimeException('The ID token has expired.');
        }

        if (($claims['sub'] ?? '') === '') {
            throw new RuntimeException('The ID token is missing a subject.');
        }

        return $claims;
    }

    private function metadata(): array
    {
        static $metadata = null;

        if ($metadata !== null) {
            return $metadata;
        }

        $url = AuthConfig::discoveryUrl();
        if ($url === '') {
            throw new RuntimeException('OIDC discovery URL is not configured.');
        }

        $metadata = $this->httpGet($url);

        foreach (['authorization_endpoint', 'token_endpoint', 'jwks_uri', 'issuer'] as $key) {
            if (empty($metadata[$key])) {
                throw new RuntimeException('OIDC discovery metadata is incomplete.');
            }
        }

        return $metadata;
    }

    private function matchingJwk(string $jwksUrl, string $kid): array
    {
        $jwks = $this->httpGet($jwksUrl);
        foreach (($jwks['keys'] ?? []) as $key) {
            if (($key['kid'] ?? '') === $kid) {
                return $key;
            }
        }

        throw new RuntimeException('No matching signing key was found for the ID token.');
    }

    private function assertConfigured(): void
    {
        foreach (['AUTH0_CLIENT_ID', 'AUTH0_CLIENT_SECRET'] as $key) {
            $value = AuthConfig::get($key);
            if ($value === '' || str_starts_with($value, 'replace')) {
                throw new RuntimeException('OIDC client credentials are not configured.');
            }
        }
    }

    private function callbackUrl(): string
    {
        return AuthConfig::baseUrl() . '/index.php?page=auth_callback';
    }

    private function httpGet(string $url): array
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 5,
                'ignore_errors' => true,
            ],
        ]);

        $body = file_get_contents($url, false, $context);
        if ($body === false) {
            throw new RuntimeException('OIDC endpoint could not be reached.');
        }

        return $this->jsonDecode($body);
    }

    private function httpPost(string $url, array $data): array
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
                'timeout' => 5,
                'ignore_errors' => true,
            ],
        ]);

        $body = file_get_contents($url, false, $context);
        if ($body === false) {
            throw new RuntimeException('OIDC token endpoint could not be reached.');
        }

        return $this->jsonDecode($body);
    }

    private function splitJwt(string $token): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new RuntimeException('The ID token is malformed.');
        }

        return $parts;
    }

    private function jsonDecode(string $json): array
    {
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('OIDC JSON response could not be decoded.');
        }

        return $decoded;
    }

    private function base64UrlDecode(string $value): string
    {
        $padding = strlen($value) % 4;
        if ($padding > 0) {
            $value .= str_repeat('=', 4 - $padding);
        }

        $decoded = base64_decode(strtr($value, '-_', '+/'), true);
        if ($decoded === false) {
            throw new RuntimeException('Base64URL value could not be decoded.');
        }

        return $decoded;
    }

    private function jwkToPem(array $jwk): string
    {
        if (($jwk['kty'] ?? '') !== 'RSA' || empty($jwk['n']) || empty($jwk['e'])) {
            throw new RuntimeException('OIDC signing key is not an RSA key.');
        }

        $modulus = $this->base64UrlDecode($jwk['n']);
        $exponent = $this->base64UrlDecode($jwk['e']);

        $components = $this->asn1Sequence(
            $this->asn1Integer($modulus) .
            $this->asn1Integer($exponent)
        );

        $publicKey = $this->asn1Sequence(
            $this->asn1Sequence(
                $this->asn1ObjectIdentifier('1.2.840.113549.1.1.1') .
                $this->asn1Null()
            ) .
            $this->asn1BitString($components)
        );

        return "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split(base64_encode($publicKey), 64, "\n") .
            "-----END PUBLIC KEY-----\n";
    }

    private function asn1Length(int $length): string
    {
        if ($length < 128) {
            return chr($length);
        }

        $encoded = '';
        while ($length > 0) {
            $encoded = chr($length & 0xff) . $encoded;
            $length >>= 8;
        }

        return chr(0x80 | strlen($encoded)) . $encoded;
    }

    private function asn1Sequence(string $value): string
    {
        return "\x30" . $this->asn1Length(strlen($value)) . $value;
    }

    private function asn1Integer(string $value): string
    {
        if (ord($value[0]) > 0x7f) {
            $value = "\x00" . $value;
        }

        return "\x02" . $this->asn1Length(strlen($value)) . $value;
    }

    private function asn1ObjectIdentifier(string $oid): string
    {
        $parts = array_map('intval', explode('.', $oid));
        $encoded = chr($parts[0] * 40 + $parts[1]);

        for ($i = 2; $i < count($parts); $i++) {
            $value = $parts[$i];
            $bytes = chr($value & 0x7f);
            while ($value >>= 7) {
                $bytes = chr(($value & 0x7f) | 0x80) . $bytes;
            }
            $encoded .= $bytes;
        }

        return "\x06" . $this->asn1Length(strlen($encoded)) . $encoded;
    }

    private function asn1Null(): string
    {
        return "\x05\x00";
    }

    private function asn1BitString(string $value): string
    {
        $value = "\x00" . $value;

        return "\x03" . $this->asn1Length(strlen($value)) . $value;
    }
}
