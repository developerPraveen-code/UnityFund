<?php

// USER STORY #11: User Admin Login
// Boundary: AdminDashboard displays the landing page after successful User Admin login.

require_once __DIR__ . '/../../login/boundary/UserSession.php';

$userSession = new UserSession();
$userSession->requireLogin();

$user = $_SESSION['user'];

if ($user['role'] !== 'user_admin') {
    header('Location: /index.php?page=dashboard');
    exit();
}

$totalProfiles = 2;
$totalAccounts = 4;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Admin Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<div class="app-layout">

    <aside class="sidebar">

        <div>
            <div class="sidebar-logo">
                <img src="/images/unityfund-logo.png" alt="UnityFund Logo" class="sidebar-logo-img">
            </div>

            <nav class="sidebar-menu">

                <a href="/index.php?page=admin_dashboard" class="sidebar-link active">
                    ▦ Overview
                </a>

                <a href="/index.php?page=create_user_account" class="sidebar-create-btn">
                    + New Account
                </a>

                <a href="/index.php?page=view_user_accounts" class="sidebar-link">
                    ▣ View Accounts
                </a>

                <a href="/index.php?page=search_user_account" class="sidebar-link">
                    🔍 Search Account
                </a>

                <a href="/index.php?page=create_user_profile" class="sidebar-link">
                    + Create Profile
                </a>

                <a href="/index.php?page=view_user_profiles" class="sidebar-link">
                    👤 View Profiles
                </a>

                <a href="/index.php?page=search_user_profile" class="sidebar-link">
                    🔍 Search Profiles
                </a>

                <a href="/index.php?page=logout" class="sidebar-link">
                    ⎋ Logout
                </a>

            </nav>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>

            <div class="user-info">
                <strong><?= htmlspecialchars($user['name']) ?></strong>
                <span>User Admin</span>
            </div>
        </div>

    </aside>

    <main class="main-content">

        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-icon">☰</div>

                <div class="topbar-title">
                    <h2>Welcome back, <?= htmlspecialchars($user['name']) ?></h2>
                    <p>User Admin Dashboard</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg">

            <div class="stats-grid">

                <div class="stat-card green">
                    <p>User Accounts</p>
                    <h3><?= $totalAccounts ?></h3>
                    <span>Managed accounts</span>
                </div>

                <div class="stat-card blue">
                    <p>User Profiles</p>
                    <h3><?= $totalProfiles ?></h3>
                    <span>Profile records</span>
                </div>

                <div class="stat-card purple">
                    <p>Roles</p>
                    <h3>4</h3>
                    <span>Admin, Donee, Fundraiser, PM</span>
                </div>

                <div class="stat-card yellow">
                    <p>Pending Actions</p>
                    <h3>0</h3>
                    <span>No pending tasks</span>
                </div>

            </div>

            <div class="activity-section">
                <h3>Recent Activity</h3>
                <div class="activity-box">
                    Use the sidebar to manage user accounts and profiles.
                </div>
            </div>

        </section>

    </main>

</div>

</body>
</html>

