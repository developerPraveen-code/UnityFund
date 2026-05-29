<?php

// USER STORY #32: Search Completed FRA History
// BCE Role: Boundary
// Allows Fundraiser to search completed FRA history.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SearchHistoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new SearchHistoryController();
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $results = $controller->searchCompletedFRA(
        $_SESSION['user']['id'],
        $_POST['keyword'] ?? ''
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Completed FRA History</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Search Completed FRA History</h1>

<form method="POST">
    <div class="form-group">
        <label>Keyword</label>
        <input type="text" name="keyword" placeholder="Search completed FRA...">
    </div>

    <button class="btn-primary">Search</button>
</form>

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
    <td>$<?= number_format($fra['goalAmount'], 2) ?></td>
</tr>
<?php endforeach; ?>
</table>

<a href="/index.php?page=fundraiser_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>