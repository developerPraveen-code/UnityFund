<?php

require_once __DIR__ . '/../../auth/OidcClient.php';
require_once __DIR__ . '/../../auth/AuthConfig.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try {
    $client = new OidcClient();
    header('Location: ' . $client->authorizationUrl());
} catch (Throwable $error) {
    http_response_code(503);
    echo '<h1>Login unavailable</h1>';
    echo '<p>UnityFund OIDC login could not be started. Check your environment configuration.</p>';

    if (AuthConfig::bool('APP_DEBUG', false)) {
        echo '<pre>' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
    }
}

exit();
