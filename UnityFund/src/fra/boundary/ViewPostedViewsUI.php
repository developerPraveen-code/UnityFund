<?php

// USER STORY #27: View Number of Views for Posted FRA
// BCE Role: Boundary
// Allows Fundraiser to view number of views for each posted FRA.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewPostedViewsController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new ViewPostedViewsController();
$fraList = $controller->getPostedFRAList($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Posted FRA Views</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Posted FRA Views</h1>

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

<a href="/index.php?page=fundraiser_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>