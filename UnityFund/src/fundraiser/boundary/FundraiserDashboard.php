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

    <?php $activePage = 'overview'; require_once __DIR__ . '/FundraiserSidebar.php'; ?>

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
