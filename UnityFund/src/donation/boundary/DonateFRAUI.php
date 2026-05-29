<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/DonateFRAController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$user = $_SESSION['user'];
$controller = new DonateFRAController();

$fraId = (int) ($_GET['fraId'] ?? 0);
$selectedFRA = $fraId ? $controller->getFRA($fraId) : null;
$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedFraId = (int) ($_POST['fraId'] ?? 0);
    $amount = (float) ($_POST['amount'] ?? 0);

    $message = $controller->makeDonation($user['id'], $postedFraId, $amount);

    if (str_starts_with($message, 'Donation of')) {
        $fraId = 0;
        $selectedFRA = null;
    } else {
        $messageType = 'error';
        $selectedFRA = $controller->getFRA($postedFraId);
        $fraId = $postedFraId;
    }
}

$activeFRAList = $controller->getActiveFRA();
$activePage = 'donate';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make Donation</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="app-layout">

    <?php require_once __DIR__ . '/../../fundraiser/boundary/FundraiserSidebar.php'; ?>

    <main class="main-content">

        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-icon">☰</div>
                <div class="topbar-title">
                    <h2>Make Donation</h2>
                    <p>Fundraiser</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg">

            <?php if ($message): ?>
                <div class="<?= $messageType === 'error' ? 'error-message' : 'success-message' ?>"
                     style="margin-bottom:20px">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if ($selectedFRA): ?>

                <div class="dashboard-card" style="max-width:560px;margin:0 auto">
                    <h2 style="margin-top:0">Donate to Campaign</h2>

                    <p><strong><?= htmlspecialchars($selectedFRA['title']) ?></strong></p>
                    <p style="color:#6b7280;font-size:13px;margin-top:4px">
                        <?= htmlspecialchars($selectedFRA['category']) ?> &bull;
                        Goal: $<?= number_format((float)$selectedFRA['goalAmount'], 2) ?> &bull;
                        Raised: $<?= number_format((float)$selectedFRA['amountRaised'], 2) ?>
                    </p>

                    <form method="POST" style="margin-top:20px">
                        <input type="hidden" name="fraId" value="<?= $selectedFRA['fraId'] ?>">

                        <div class="form-group">
                            <label>Donation Amount ($)</label>
                            <input type="number" name="amount" step="0.01" min="0.01"
                                   placeholder="e.g. 50.00" required>
                        </div>

                        <button class="btn-primary" type="submit">Confirm Donation</button>
                    </form>

                    <a href="/index.php?page=make_donation" class="secondary-btn">
                        Back to Campaigns
                    </a>
                </div>

            <?php else: ?>

                <h3 style="margin-top:0;margin-bottom:16px">Active Campaigns</h3>

                <?php if (empty($activeFRAList)): ?>
                    <p style="color:#6b7280">No active campaigns available at the moment.</p>
                <?php else: ?>
                    <table class="table">
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Goal</th>
                            <th>Raised</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($activeFRAList as $fra): ?>
                        <tr>
                            <td><?= htmlspecialchars($fra['title']) ?></td>
                            <td><?= htmlspecialchars($fra['category']) ?></td>
                            <td>$<?= number_format((float)$fra['goalAmount'], 2) ?></td>
                            <td>$<?= number_format((float)$fra['amountRaised'], 2) ?></td>
                            <td>
                                <a class="action-link"
                                   href="/index.php?page=make_donation&fraId=<?= $fra['fraId'] ?>">
                                    Donate
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>

            <?php endif; ?>

        </section>

    </main>

</div>

</body>
</html>
