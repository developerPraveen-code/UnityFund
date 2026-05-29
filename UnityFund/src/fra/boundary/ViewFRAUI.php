<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$user = $_SESSION['user'];

require_once __DIR__ . '/../controller/ViewFRAController.php';

$controller = new ViewFRAController();
$fraList = $controller->getFRAList($user['id']);

$activePage = 'my_campaigns';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Campaigns</title>
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
                    <h2>My Campaigns</h2>
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
                    <th>Raised</th>
                    <th>Goal</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($fraList as $fra): ?>
                <tr>
                    <td><?= $fra['fraId'] ?></td>
                    <td><?= htmlspecialchars($fra['title']) ?></td>
                    <td><?= $fra['status'] ?></td>
                    <td>$<?= number_format((float)$fra['amountRaised'], 2) ?></td>
                    <td>$<?= number_format((float)$fra['goalAmount'], 2) ?></td>
                    <td>
                        <a class="action-link"
                           href="/index.php?page=edit_fra&fraId=<?= $fra['fraId'] ?>">Edit</a>
                        &nbsp;|&nbsp;
                        <a class="action-link"
                           href="/index.php?page=disable_fra&fraId=<?= $fra['fraId'] ?>">Disable</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

        </section>

    </main>

</div>

</body>
</html>
