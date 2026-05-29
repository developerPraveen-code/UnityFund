<?php

// USER STORY #28: View Shortlist Count

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewFRAController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$user = $_SESSION['user'];
$controller = new ViewFRAController();
$fraList = $controller->getFRAList($user['id']);

$activePage = 'shortlist';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shortlist Count</title>
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
                    <h2>FRA Shortlist Count</h2>
                    <p>Fundraiser</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg">

            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Shortlisted</th>
                </tr>
                <?php foreach ($fraList as $fra): ?>
                <tr>
                    <td><?= $fra['fraId'] ?></td>
                    <td><?= htmlspecialchars($fra['title']) ?></td>
                    <td><?= htmlspecialchars($fra['status']) ?></td>
                    <td><?= $fra['shortlistCount'] ?? 0 ?></td>
                </tr>
                <?php endforeach; ?>
            </table>

        </section>

    </main>

</div>

</body>
</html>
