<?php
require_once __DIR__ . '/../../login/boundary/UserSession.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'donee') {
    header('Location: /index.php?page=login');
    exit();
}
?>

<?php

require_once __DIR__ . '/../controller/ViewSavedFRAController.php';

$controller = new ViewSavedFRAController();

$savedFRA = $controller->getSavedFRA(
    $_SESSION['user']['id']
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Saved FRA</title>
<link rel="stylesheet" href="/css/style.css">
</head>

<body>

<div class="page-center">

<section class="dashboard-card">

<h1>Saved Fundraising Activities</h1>

<table class="table">

<tr>
<th>Title</th>
<th>Category</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php foreach ($savedFRA as $fra): ?>

<tr>

<td><?= htmlspecialchars($fra['title']) ?></td>

<td><?= htmlspecialchars($fra['category']) ?></td>

<td><?= $fra['status'] ?></td>

<td>

<a class="action-link"
href="/index.php?page=view_fra_details&fraId=<?= $fra['fraId'] ?>">
View
</a>

</td>

</tr>

<?php endforeach; ?>

</table>

<a href="/index.php?page=donee_dashboard"
class="secondary-btn">
Back
</a>

</section>

</div>

</body>
</html>