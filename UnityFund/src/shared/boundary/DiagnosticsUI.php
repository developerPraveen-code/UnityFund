<?php

require_once __DIR__ . '/../../auth/AuthConfig.php';

$databaseUrl = AuthConfig::get('DATABASE_URL', AuthConfig::get('POSTGRES_URL'));
$databaseParts = $databaseUrl !== '' ? parse_url($databaseUrl) : [];
$safeDatabase = [
    'DATABASE_URL present' => $databaseUrl !== '' ? 'yes' : 'no',
    'POSTGRES_URL present' => AuthConfig::get('POSTGRES_URL') !== '' ? 'yes' : 'no',
    'DB_HOST fallback' => AuthConfig::get('DB_HOST', 'localhost'),
    'Parsed host' => is_array($databaseParts) ? ($databaseParts['host'] ?? '') : '',
    'Parsed port' => is_array($databaseParts) ? (string) ($databaseParts['port'] ?? '') : '',
    'Parsed database' => is_array($databaseParts) ? ltrim($databaseParts['path'] ?? '', '/') : '',
    'APP_BASE_URL' => AuthConfig::baseUrl(),
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diagnostics | UnityFund</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
<main class="main-content" style="margin-left:0; min-height:100vh;">
    <header class="topbar">
        <div class="topbar-title">
            <h2>UnityFund Diagnostics</h2>
            <p>Safe environment check. Passwords and secrets are not displayed.</p>
        </div>
    </header>

    <section class="dashboard-bg">
        <div class="dashboard-card">
            <?php foreach ($safeDatabase as $label => $value): ?>
                <p>
                    <strong><?= htmlspecialchars($label) ?>:</strong>
                    <?= htmlspecialchars($value) ?>
                </p>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</body>
</html>
