<?php

// USER STORY #38: Update FRA Category
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/UpdateFRACategoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'platform_manager') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new UpdateFRACategoryController();

$categoryId = (int) ($_GET['categoryId'] ?? 0);
$category = $controller->getCategory($categoryId);

if (!$category) {
    die('FRA category not found.');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $controller->updateCategory(
        $categoryId,
        trim($_POST['categoryName']),
        trim($_POST['description'])
    );

    $category = $controller->getCategory($categoryId);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update FRA Category</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Update FRA Category</h1>

<?php if ($message): ?>
<div class="success-message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Category Name</label>
        <input type="text" name="categoryName"
               value="<?= htmlspecialchars($category['categoryName']) ?>" required>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" required><?= htmlspecialchars($category['description']) ?></textarea>
    </div>

    <button type="submit" class="btn-primary">Update Category</button>

</form>

<a href="/index.php?page=view_category" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>