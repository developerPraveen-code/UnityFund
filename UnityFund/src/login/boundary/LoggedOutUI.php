<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signed Out | UnityFund</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
<div class="page-center">
    <section class="login-card">
        <div class="logo-pill">
            <img src="/images/unityfund-logo.png" alt="UnityFund Logo" class="login-logo-full">
        </div>

        <h1>Signed out</h1>
        <p class="subtitle">You have been signed out of UnityFund.</p>

        <a href="/index.php?page=login" class="btn-primary">Sign in with OIDC</a>
    </section>
</div>
</body>
</html>
