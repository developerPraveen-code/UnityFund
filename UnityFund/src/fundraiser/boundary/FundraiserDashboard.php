<?php

// USER STORY #30: Fundraiser Login
// Boundary: FundraiserDashboard displays the landing page after successful Fundraiser login.
// Links to Sprint 2 and Sprint 3 Fundraiser functionalities.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../../fra/controller/ViewFRAController.php';
require_once __DIR__ . '/../../fra/entity/FundraisingActivity.php';
require_once __DIR__ . '/../../donation/entity/Donation.php';

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

$activities = [];
try {
    $fraEntity = new FundraisingActivity();
    $donationEntity = new Donation();

    foreach ($fraEntity->getRecentCampaigns($user['id'], 5) as $fra) {
        $activities[] = [
            'type'     => 'campaign',
            'tag'      => 'Campaign',
            'verb'     => 'Created',
            'name'     => $fra['title'],
            'category' => $fra['category'],
            'status'   => $fra['status'],
            'date'     => $fra['startDate'] ? date('M j, Y', strtotime($fra['startDate'])) : '',
            'sort'     => $fra['startDate'] ?? '',
        ];
    }

    foreach ($donationEntity->getRecentDonationsByUser($user['id'], 5) as $donation) {
        $activities[] = [
            'type'     => 'donation',
            'tag'      => 'Donation',
            'verb'     => 'Donated $' . number_format((float)$donation['amount'], 2),
            'name'     => $donation['fraTitle'],
            'category' => $donation['category'],
            'status'   => $donation['status'],
            'date'     => $donation['donationDate'] ? date('M j, Y', strtotime($donation['donationDate'])) : '',
            'sort'     => $donation['donationDate'] ?? '',
        ];
    }

    usort($activities, fn($a, $b) => strcmp($b['sort'], $a['sort']));
    $activities = array_slice($activities, 0, 10);
} catch (Throwable $e) {
    $activities = [];
}
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
                <?php if (empty($activities)): ?>
                    <div class="activity-box">No recent activity yet.</div>
                <?php else: ?>
                    <div class="activity-list">
                        <?php foreach ($activities as $act): ?>
                        <div class="activity-card activity-card--<?= $act['type'] ?>">
                            <div class="activity-card-header">
                                <span class="activity-type-label activity-type-label--<?= $act['type'] ?>"><?= $act['tag'] ?></span>
                                <span class="activity-card-date"><?= htmlspecialchars($act['date']) ?></span>
                            </div>
                            <div class="activity-card-title">
                                <?= htmlspecialchars($act['verb']) ?> &ldquo;<strong><?= htmlspecialchars($act['name']) ?></strong>&rdquo;
                            </div>
                            <div class="activity-card-footer">
                                <span class="activity-category"><?= htmlspecialchars($act['category']) ?></span>
                                <span class="activity-status activity-status--<?= strtolower($act['status']) ?>"><?= htmlspecialchars($act['status']) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </section>

    </main>

</div>

</body>
</html>
