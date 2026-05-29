<?php

// USER STORY #40: Search FRA Category
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SearchCategoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'platform_manager') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new SearchCategoryController();
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $results = $controller->searchCategory(trim($_POST['searchTerm'] ?? ''));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search FRA Category</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Search FRA Category</h1>

<form method="POST">
    <div class="form-group">
        <label>Search Term</label>
        <input type="text" name="searchTerm" placeholder="Search by category name...">
    </div>

    <button type="submit" class="btn-primary">Search</button>
</form>

<table class="table">
<tr>
    <th>ID</th>
    <th>Category Name</th>
    <th>Description</th>
    <th>Status</th>
</tr>

<?php foreach ($results as $category): ?>
<tr>
    <td><?= $category['categoryId'] ?></td>
    <td><?= htmlspecialchars($category['categoryName']) ?></td>
    <td><?= htmlspecialchars($category['description']) ?></td>
    <td><?= htmlspecialchars($category['status'] ?? 'Active') ?></td>
</tr>
<?php endforeach; ?>
</table>

<a href="/index.php?page=platform_manager_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>