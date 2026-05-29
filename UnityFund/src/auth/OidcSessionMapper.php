<?php

require_once __DIR__ . '/AuthConfig.php';
require_once __DIR__ . '/../user_management/entity/ManagedUserAccount.php';

class OidcSessionMapper
{
    public function createSession(array $claims, string $source = 'oidc'): void
    {
        session_regenerate_id(true);

        $role  = $this->roleFromClaims($claims);
        $email = $claims['email'] ?? '';

        $userId = $this->lookupUserId($email, $role);

        $_SESSION['user'] = [
            'id'          => $userId,
            'email'       => $email,
            'role'        => $role,
            'name'        => $claims['name'] ?? $email ?? 'Authenticated User',
            'sub'         => $claims['sub'] ?? '',
            'auth_source' => $source,
        ];
    }

    private function lookupUserId(string $email, string $role): int
    {
        if ($email === '') {
            return $this->idForRole($role);
        }

        try {
            $accountEntity = new ManagedUserAccount();
            $account = $accountEntity->getAccountByEmail($email);
            if ($account && isset($account['userId'])) {
                return (int) $account['userId'];
            }
        } catch (Throwable $e) {
            // DB unavailable — fall back to role-based placeholder
        }

        return $this->idForRole($role);
    }

    private function roleFromClaims(array $claims): string
    {
        $claimName = AuthConfig::get('OIDC_ROLE_CLAIM', 'https://unityfund.local/role');
        $role = $claims[$claimName] ?? $claims['role'] ?? AuthConfig::get('DEFAULT_USER_ROLE', 'fundraiser');

        if (is_array($role)) {
            $role = $role[0] ?? '';
        }

        return in_array($role, AuthConfig::validRoles(), true) ? $role : 'fundraiser';
    }

    private function idForRole(string $role): int
    {
        return match ($role) {
            'user_admin' => 1,
            'donee' => 2,
            'fundraiser' => 3,
            'platform_manager' => 4,
            default => 0,
        };
    }
}
