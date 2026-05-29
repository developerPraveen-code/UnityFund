<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewFRADetailsController.php';

$userSession = new UserSession();
$userSession->requireLogin();

$controller = new ViewFRADetailsController();

$fraId = (int) ($_GET['fraId'] ?? 0);
$fra = $controller->getFRA($fraId);

if ($fra === null) {
    echo 'FRA not found.';
    exit();
}

$backPage = ($_SESSION['user']['role'] === 'fundraiser')
    ? '/index.php?page=search_my_fra'
    : '/index.php?page=search_all_fra';

?>

<!DOCTYPE html>
<html>
<head>
<title>View FRA Details</title>
<link rel="stylesheet" href="/css/style.css">
</head>

<body>

<div class="page-center">

<section class="dashboard-card">

<h1><?= htmlspecialchars($fra['title']) ?></h1>

<p><?= htmlspecialchars($fra['description']) ?></p>

<p><strong>Category:</strong> <?= htmlspecialchars($fra['category']) ?></p>

<p><strong>Status:</strong> <?= htmlspecialchars($fra['status']) ?></p>

<p><strong>Goal:</strong> $<?= number_format((float)$fra['goalAmount'], 2) ?></p>

<p><strong>Raised:</strong> $<?= number_format((float)$fra['amountRaised'], 2) ?></p>

<p><strong>Views:</strong> <?= htmlspecialchars($fra['views']) ?></p>

<a href="<?= $backPage ?>" class="secondary-btn">
Back
</a>

</section>

</div>

</body>
</html>