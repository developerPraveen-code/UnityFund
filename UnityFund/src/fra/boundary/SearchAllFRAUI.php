<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SearchAllFRAController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'donee') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new SearchAllFRAController();

$keyword = $_POST['keyword'] ?? '';
$results = $controller->searchAllFRA($keyword);

?>

<!DOCTYPE html>
<html>
<head>
<title>Search All FRA</title>
<link rel="stylesheet" href="/css/style.css">
</head>

<body>

<div class="page-center">

<section class="dashboard-card">

<h1>Search All FRA</h1>

<?php if (($_GET['message'] ?? '') === 'saved'): ?>
    <div class="success-message">
        Fundraising activity saved successfully.
    </div>
<?php elseif (($_GET['message'] ?? '') === 'already_saved'): ?>
    <div class="success-message">
        This fundraising activity has already been saved.
    </div>
<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Keyword</label>
<input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
</div>

<button class="btn-primary" type="submit">
Search
</button>

</form>

<?php if (empty($results)): ?>

<p>No fundraising activities found.</p>

<?php else: ?>

<table class="table">
<tr>
<th>Title</th>
<th>Category</th>
<th>Goal Amount</th>
<th>Amount Raised</th>
<th>Action</th>
</tr>

<?php foreach ($results as $fra): ?>

<tr>
<td><?= htmlspecialchars($fra['title']) ?></td>
<td><?= htmlspecialchars($fra['category']) ?></td>
<td>$<?= number_format((float)$fra['goalAmount'], 2) ?></td>
<td>$<?= number_format((float)$fra['amountRaised'], 2) ?></td>

<td>
<a class="action-link"
href="/index.php?page=view_fra_details&fraId=<?= $fra['fraId'] ?>">
View
</a>

|

<a class="action-link"
href="/index.php?page=save_favorite&fraId=<?= $fra['fraId'] ?>">
Save to Favorites
</a>
</td>
</tr>

<?php endforeach; ?>

</table>

<?php endif; ?>

<a href="/index.php?page=donee_dashboard" class="secondary-btn">
Back
</a>

</section>

</div>

</body>
</html>