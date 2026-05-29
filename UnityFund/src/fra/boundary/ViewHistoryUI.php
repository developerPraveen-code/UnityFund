<?php

// USER STORY #33: View Completed FRA History

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewHistoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$user = $_SESSION['user'];
$controller = new ViewHistoryController();
$historyList = $controller->getCompletedFRA($user['id']);

$activePage = 'history';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Completed FRA History</title>
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
                    <h2>Completed FRA History</h2>
                    <p>Fundraiser</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg">

            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Goal</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($historyList as $fra): ?>
                <tr>
                    <td><?= $fra['fraId'] ?></td>
                    <td><?= htmlspecialchars($fra['title']) ?></td>
                    <td><?= htmlspecialchars($fra['category']) ?></td>
                    <td>$<?= number_format((float)$fra['goalAmount'], 2) ?></td>
                    <td><?= htmlspecialchars($fra['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>

        </section>

    </main>

</div>

</body>
</html>
