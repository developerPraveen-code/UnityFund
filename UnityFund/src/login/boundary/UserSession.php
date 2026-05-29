<?php

// USER STORY #12: User Admin Logout
// USER STORY #26: Donee Logout
// USER STORY #31: Fundraiser Logout
// USER STORY #43: Platform Manager Logout
// BCE Role: Boundary
// Boundary handles session and login validation.

class UserSession
{
    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    public function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /index.php?page=login');
            exit();
        }
    }

    public function requireRole(string $role): void
    {
        $this->requireLogin();

        if (($_SESSION['user']['role'] ?? '') !== $role) {
            header('Location: /index.php?page=dashboard');
            exit();
        }
    }

    public function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public function getUserId(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public function getUserRole(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }
}
