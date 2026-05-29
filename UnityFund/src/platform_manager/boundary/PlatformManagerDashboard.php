<?php

// USER STORY #41: Platform Manager Login
// Boundary: PlatformManagerDashboard displays the landing page after successful Platform Manager login.

require_once __DIR__ . '/../../login/boundary/UserSession.php';

$userSession = new UserSession();
$userSession->requireLogin();

$user = $_SESSION['user'];

if ($user['role'] !== 'platform_manager') {
    header('Location: /index.php?page=dashboard');
    exit();
}

$totalFRA = 0;
$totalCategories = 3;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Platform Manager Dashboard</title>
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

                <a href="/index.php?page=platform_manager_dashboard" class="sidebar-link active">
                    ▦ Overview
                </a>

                <a href="/index.php?page=create_fra_category" class="sidebar-create-btn">
                    + New Category
                </a>

                <a href="/index.php?page=view_category" class="sidebar-link">
                    ▣ View Categories
                </a>

                <a href="/index.php?page=search_category" class="sidebar-link">
                    🔍 Search Category
                </a>

                <a href="/index.php?page=monthly_report" class="sidebar-link">
                    📊 Monthly Report
                </a>

                <a href="/index.php?page=daily_report" class="sidebar-link">
                    📅 Daily Report
                </a>

                <a href="/index.php?page=weekly_report" class="sidebar-link">
                    📈 Weekly Report
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
                <span>Platform Manager</span>
            </div>
        </div>

    </aside>

    <main class="main-content">

        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-icon">☰</div>

                <div class="topbar-title">
                    <h2>Welcome back, <?= htmlspecialchars($user['name']) ?></h2>
                    <p>Platform Manager Dashboard</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg">

            <div class="stats-grid">

                <div class="stat-card green">
                    <p>Total FRA</p>
                    <h3><?= $totalFRA ?></h3>
                    <span>All platform activities</span>
                </div>

                <div class="stat-card blue">
                    <p>Categories</p>
                    <h3><?= $totalCategories ?></h3>
                    <span>Available FRA categories</span>
                </div>

                <div class="stat-card purple">
                    <p>Reports</p>
                    <h3>3</h3>
                    <span>Daily, weekly, monthly reports</span>
                </div>

                <div class="stat-card yellow">
                    <p>System Alerts</p>
                    <h3>0</h3>
                    <span>No alerts</span>
                </div>

            </div>

            <div class="activity-section">
                <h3>Recent Activity</h3>
                <div class="activity-box">
                    Use the sidebar to manage categories and view reports.
                </div>
            </div>

        </section>

    </main>

</div>

</body>
</html>
