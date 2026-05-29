<?php

class AuthConfig
{
    private static bool $loaded = false;

    public static function get(string $key, string $default = ''): string
    {
        self::loadEnv();

        $value = getenv($key);
        if ($value === false) {
            return $default;
        }

        return trim($value);
    }

    public static function bool(string $key, bool $default): bool
    {
        $value = self::get($key, $default ? 'true' : 'false');

        return in_array(strtolower($value), ['1', 'true', 'yes', 'on'], true);
    }

    public static function baseUrl(): string
    {
        return rtrim(self::get('APP_BASE_URL', 'http://localhost'), '/');
    }

    public static function discoveryUrl(): string
    {
        $configured = self::get('OIDC_DISCOVERY_URL');
        if ($configured !== '') {
            return $configured;
        }

        $domain = rtrim(self::get('AUTH0_DOMAIN'), '/');
        if ($domain === '') {
            return '';
        }

        return 'https://' . $domain . '/.well-known/openid-configuration';
    }

    public static function validRoles(): array
    {
        return ['user_admin', 'donee', 'fundraiser', 'platform_manager'];
    }

    private static function loadEnv(): void
    {
        if (self::$loaded) {
            return;
        }

        self::$loaded = true;
        $envPath = dirname(__DIR__, 2) . '/.env';
        if (!is_file($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode('=', $line, 2));
            if (getenv($key) !== false) {
                continue;
            }

            $value = trim($value, "\"'");
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}
