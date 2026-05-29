<?php

// USER STORY #25: Donee Login
// Boundary: DoneeDashboard displays landing page after successful Donee login.
// Links to Sprint 2 and Sprint 3 Donee functionalities.

require_once __DIR__ . '/../../login/boundary/UserSession.php';

$userSession = new UserSession();
$userSession->requireLogin();

$user = $_SESSION['user'];

if ($user['role'] !== 'donee') {
    header('Location: /index.php?page=dashboard');
    exit();
}

$totalSaved = isset($_SESSION['favorite_list']) ? count($_SESSION['favorite_list']) : 0;
$totalAvailable = isset($_SESSION['fra_list']) ? count($_SESSION['fra_list']) : 0;
$totalDonations = isset($_SESSION['donation_list']) ? count($_SESSION['donation_list']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donee Dashboard</title>
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

                <a href="/index.php?page=donee_dashboard" class="sidebar-link active">
                    ▦ Overview
                </a>

                <a href="/index.php?page=search_all_fra" class="sidebar-create-btn">
                    🔍 Search All FRA
                </a>

                <a href="/index.php?page=view_saved_fra" class="sidebar-link">
                    ★ Saved FRA
                </a>

                <a href="/index.php?page=search_favorite_fra" class="sidebar-link">
                    🔎 Search Favourite FRA
                </a>

                <a href="/index.php?page=view_donation_history" class="sidebar-link">
                    📜 Donation History
                </a>

                <a href="/index.php?page=search_donation_history" class="sidebar-link">
                    🔍 Search Donations
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
                <span>Donee</span>
            </div>
        </div>

    </aside>

    <main class="main-content">

        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-icon">☰</div>

                <div class="topbar-title">
                    <h2>Welcome back, <?= htmlspecialchars($user['name']) ?></h2>
                    <p>Donee Dashboard</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg">

            <div class="stats-grid">

                <div class="stat-card green">
                    <p>Available FRA</p>
                    <h3><?= $totalAvailable ?></h3>
                    <span>Campaigns on platform</span>
                </div>

                <div class="stat-card blue">
                    <p>Saved FRA</p>
                    <h3><?= $totalSaved ?></h3>
                    <span>Your favourites</span>
                </div>

                <div class="stat-card purple">
                    <p>Donations Made</p>
                    <h3><?= $totalDonations ?></h3>
                    <span>Your donation records</span>
                </div>

                <div class="stat-card yellow">
                    <p>Giving Progress</p>
                    <h3>0</h3>
                    <span>Track your donation impact</span>
                </div>

            </div>

            <div class="activity-section">
                <h3>Recent Activity</h3>
                <div class="activity-box">
                    No recent activity yet.
                </div>
            </div>

        </section>

    </main>

</div>

</body>
</html>
