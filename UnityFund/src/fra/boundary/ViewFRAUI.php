<?php
require_once __DIR__ . '/../../login/boundary/UserSession.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}
?>

<?php

require_once __DIR__ . '/../controller/ViewFRAController.php';

$controller = new ViewFRAController();

$fraList = $controller->getFRAList($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View My FRA</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">

<section class="dashboard-card">

<h1>My Fundraising Activities</h1>

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
<td>$<?= $fra['amountRaised'] ?></td>
<td>$<?= $fra['goalAmount'] ?></td>

<td>
<a class="action-link"
href="/index.php?page=edit_fra&fraId=<?= $fra['fraId'] ?>">
Edit
</a>

|

<a class="action-link"
href="/index.php?page=disable_fra&fraId=<?= $fra['fraId'] ?>">
Disable
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

<a href="/index.php?page=fundraiser_dashboard" class="secondary-btn">
Back
</a>

</section>

</div>

</body>
</html>