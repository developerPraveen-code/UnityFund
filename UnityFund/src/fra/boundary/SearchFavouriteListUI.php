<?php

// USER STORY #23: Search Favourite FRA
// BCE Role: Boundary
// Allows Donee to search within saved/favourite FRA list.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SearchFavouriteListController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'donee') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new SearchFavouriteListController();
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $results = $controller->getSearchShortlistFRA(
        $_SESSION['user']['id'],
        $_POST['keyword'] ?? ''
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Favourite FRA</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Search Favourite FRA</h1>

<form method="POST">
    <div class="form-group">
        <label>Keyword</label>
        <input type="text" name="keyword" placeholder="Search saved FRA...">
    </div>

    <button class="btn-primary">Search</button>
</form>

<table class="table">
<tr>
    <th>Title</th>
    <th>Category</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach ($results as $fra): ?>
<tr>
    <td><?= htmlspecialchars($fra['title']) ?></td>
    <td><?= htmlspecialchars($fra['category']) ?></td>
    <td><?= htmlspecialchars($fra['status']) ?></td>
    <td>
        <a class="action-link" href="/index.php?page=view_fra_details&fraId=<?= $fra['fraId'] ?>">
            View
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<a href="/index.php?page=donee_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>