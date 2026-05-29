<?php

// USER STORY #30: Fundraiser Login
// Boundary: FundraiserDashboard displays the landing page after successful Fundraiser login.
// Links to Sprint 2 and Sprint 3 Fundraiser functionalities.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../../fra/controller/ViewFRAController.php';

$userSession = new UserSession();
$userSession->requireLogin();

$user = $_SESSION['user'];

if ($user['role'] !== 'fundraiser') {
    header('Location: /index.php?page=dashboard');
    exit();
}

$databaseWarning = null;
try {
    $fraController = new ViewFRAController();
    $fraList = $fraController->getFRAList($user['id']);
} catch (Throwable $error) {
    $fraList = [];
    $databaseWarning = 'PostgreSQL is not connected, so campaign stats are empty in local preview.';
}

$totalRaised = 0;
$activeCampaigns = 0;

foreach ($fraList as $fra) {
    $totalRaised += $fra['amountRaised'];

    if ($fra['status'] === 'Active') {
        $activeCampaigns++;
    }
}

$totalDonors = 0;
$averageDonation = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fundraiser Dashboard</title>
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

                <a href="/index.php?page=fundraiser_dashboard" class="sidebar-link active">
                    ▦ Overview
                </a>

                <a href="/index.php?page=create_fra" class="sidebar-create-btn">
                    + New Campaign
                </a>

                <a href="/index.php?page=view_my_fra" class="sidebar-link">
                    ▣ My Campaigns
                </a>

                <a href="/index.php?page=search_my_fra" class="sidebar-link">
                    🔍 Search My FRA
                </a>

                <a href="/index.php?page=view_posted_views" class="sidebar-link">
                    👁 View FRA Views
                </a>

                <a href="/index.php?page=view_shortlist_count" class="sidebar-link">
                    ★ View Shortlist Count
                </a>

                <a href="/index.php?page=view_completed_history" class="sidebar-link">
                    📜 Completed History
                </a>

                <a href="/index.php?page=search_completed_history" class="sidebar-link">
                    🔎 Search History
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
                <span>Fundraiser</span>
            </div>
        </div>

    </aside>

    <main class="main-content">

        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-icon">☰</div>

                <div class="topbar-title">
                    <h2>Welcome back, <?= htmlspecialchars($user['name']) ?></h2>
                    <p>Fundraiser Dashboard</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg">

            <?php if ($databaseWarning): ?>
                <div class="error-message">
                    <?= htmlspecialchars($databaseWarning) ?>
                </div>
            <?php endif; ?>

            <div class="stats-grid">

                <div class="stat-card green">
                    <p>Total Raised</p>
                    <h3>$<?= number_format($totalRaised, 2) ?></h3>
                    <span>All campaigns</span>
                </div>

                <div class="stat-card blue">
                    <p>Active Campaigns</p>
                    <h3><?= $activeCampaigns ?></h3>
                    <span>Currently running</span>
                </div>

                <div class="stat-card purple">
                    <p>Total Donors</p>
                    <h3><?= $totalDonors ?></h3>
                    <span>Unique supporters</span>
                </div>

                <div class="stat-card yellow">
                    <p>Avg Donation</p>
                    <h3>$<?= number_format($averageDonation, 2) ?></h3>
                    <span>Per transaction</span>
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
