<?php

// USER STORY #32: Search Completed FRA History

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SearchHistoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$user = $_SESSION['user'];
$controller = new SearchHistoryController();
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $results = $controller->searchCompletedFRA($user['id'], $_POST['keyword'] ?? '');
}

$activePage = 'search_history';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Completed History</title>
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
                    <h2>Search Completed History</h2>
                    <p>Fundraiser</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg">

            <form method="POST" style="margin-bottom:20px;display:flex;gap:10px;align-items:flex-end">
                <div class="form-group" style="flex:1;margin:0">
                    <label>Keyword</label>
                    <input type="text" name="keyword" placeholder="Search completed FRA...">
                </div>
                <button class="btn-primary" style="width:auto;padding:0 24px">Search</button>
            </form>

            <?php if (!empty($results)): ?>
            <table class="table">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Goal</th>
                </tr>
                <?php foreach ($results as $fra): ?>
                <tr>
                    <td><?= htmlspecialchars($fra['title']) ?></td>
                    <td><?= htmlspecialchars($fra['category']) ?></td>
                    <td><?= htmlspecialchars($fra['status']) ?></td>
                    <td>$<?= number_format((float)$fra['goalAmount'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>

        </section>

    </main>

</div>

</body>
</html>
