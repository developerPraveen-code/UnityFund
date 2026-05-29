<?php

// USER STORY #27: View Number of Views for Posted FRA

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewPostedViewsController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$user = $_SESSION['user'];
$controller = new ViewPostedViewsController();
$fraList = $controller->getPostedFRAList($user['id']);

$activePage = 'views';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FRA Views</title>
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
                    <h2>Posted FRA Views</h2>
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
                    <th>Views</th>
                </tr>
                <?php foreach ($fraList as $fra): ?>
                <tr>
                    <td><?= $fra['fraId'] ?></td>
                    <td><?= htmlspecialchars($fra['title']) ?></td>
                    <td><?= htmlspecialchars($fra['status']) ?></td>
                    <td><?= $fra['views'] ?? 0 ?></td>
                </tr>
                <?php endforeach; ?>
            </table>

        </section>

    </main>

</div>

</body>
</html>
