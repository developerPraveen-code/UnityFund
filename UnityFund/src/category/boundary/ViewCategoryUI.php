<?php

// USER STORY #37: View FRA Category
// USER STORY #38: Update FRA Category
// USER STORY #39: Suspend FRA Category
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewCategoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'platform_manager') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new ViewCategoryController();
$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View FRA Categories</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>FRA Categories</h1>

<table class="table">
<tr>
    <th>ID</th>
    <th>Category Name</th>
    <th>Description</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php foreach ($categories as $category): ?>
<tr>
    <td><?= $category['categoryId'] ?></td>
    <td><?= htmlspecialchars($category['categoryName']) ?></td>
    <td><?= htmlspecialchars($category['description']) ?></td>
    <td><?= htmlspecialchars($category['status'] ?? 'Active') ?></td>
    <td>
        <a class="action-link"
           href="/index.php?page=update_fra_category&categoryId=<?= $category['categoryId'] ?>">
            Edit
        </a>

        |

        <?php if (($category['status'] ?? 'Active') !== 'Suspended'): ?>
            <a class="action-link"
               href="/index.php?page=suspend_fra_category&categoryId=<?= $category['categoryId'] ?>">
                Suspend
            </a>
        <?php else: ?>
            Suspended
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<a href="/index.php?page=platform_manager_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>