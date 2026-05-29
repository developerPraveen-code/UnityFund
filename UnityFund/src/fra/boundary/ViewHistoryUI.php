<?php

// USER STORY #33: View Completed FRA History
// BCE Role: Boundary
// Allows Fundraiser to view completed FRA history.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewHistoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new ViewHistoryController();
$historyList = $controller->getCompletedFRA($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Completed FRA History</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Completed FRA History</h1>

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
    <td>$<?= number_format($fra['goalAmount'], 2) ?></td>
    <td><?= htmlspecialchars($fra['status']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<a href="/index.php?page=fundraiser_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>