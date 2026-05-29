<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/CreateFRAController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new CreateFRAController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $controller->createFRA(
        $_SESSION['user']['id'],
        $_POST['title'],
        $_POST['description'],
        (float) $_POST['goalAmount'],
        $_POST['category']
    );
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create FRA</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">

<section class="dashboard-card">

<h1>Create Fundraising Activity</h1>

<?php if ($message): ?>
<div class="success-message">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Title</label>
<input type="text" name="title" required>
</div>

<div class="form-group">
<label>Description</label>
<textarea name="description" required></textarea>
</div>

<div class="form-group">
<label>Goal Amount</label>
<input type="number" name="goalAmount" step="0.01" required>
</div>

<div class="form-group">
<label>Category</label>
<input type="text" name="category" required>
</div>

<button class="btn-primary" type="submit">
Create FRA
</button>

<a href="/index.php?page=fundraiser_dashboard" class="secondary-btn">
Back
</a>

</form>

</section>

</div>

</body>
</html>