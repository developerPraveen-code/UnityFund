<?php

// USER STORY #28: View Shortlist Count
// BCE Role: Boundary
// Allows Fundraiser to view how many times an FRA has been shortlisted/saved.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewFRAController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new ViewFRAController();
$fraList = $controller->getFRAList($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Shortlist Count</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>FRA Shortlist Count</h1>

<table class="table">
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Status</th>
    <th>Shortlisted Count</th>
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

<a href="/index.php?page=fundraiser_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>