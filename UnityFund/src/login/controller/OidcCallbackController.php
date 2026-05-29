<?php

require_once __DIR__ . '/../../auth/OidcClient.php';
require_once __DIR__ . '/../../auth/OidcSessionMapper.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try {
    $client = new OidcClient();
    $claims = $client->authenticateCallback($_GET);

    $mapper = new OidcSessionMapper();
    $mapper->createSession($claims);

    $role = $_SESSION['user']['role'] ?? '';
    $target = match ($role) {
        'user_admin' => '/index.php?page=admin_dashboard',
        'donee' => '/index.php?page=donee_dashboard',
        'platform_manager' => '/index.php?page=platform_manager_dashboard',
        default => '/index.php?page=fundraiser_dashboard',
    };

    header('Location: ' . $target);
} catch (Throwable $error) {
    $_SESSION = [];
    http_response_code(401);
    echo '<h1>Authentication failed</h1>';
    echo '<p>Your UnityFund login token could not be validated. Please try again.</p>';
}

exit();
