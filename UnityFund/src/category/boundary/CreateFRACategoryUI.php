<?php

// USER STORY #48: Create FRA Category
// BCE Role: Boundary
// Allows Platform Manager to create a new FRA category.

require_once __DIR__ . '/../../login/boundary/UserSession.php';

$userSession = new UserSession();
$userSession->requireLogin();

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/CreateFRACategoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'platform_manager') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new CreateFRACategoryController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $controller->createCategory(
        trim($_POST['categoryName'] ?? ''),
        trim($_POST['description'] ?? '')
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create FRA Category</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Create FRA Category</h1>

<?php if ($message): ?>
    <div class="success-message">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Category Name</label>
        <input type="text" name="categoryName" required>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" required></textarea>
    </div>

    <button class="btn-primary" type="submit">
        Create Category
    </button>

</form>

<a href="/index.php?page=platform_manager_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>