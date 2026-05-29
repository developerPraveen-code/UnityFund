<?php

// USER STORY: Create User Account
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/CreateUserAccountController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new CreateUserAccountController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $controller->createAccount(
        trim($_POST['username']),
        trim($_POST['email']),
        trim($_POST['role'])
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create User Account</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Create User Account</h1>

<?php if ($message): ?>
<div class="success-message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Role</label>
        <select name="role" required>
            <option value="donee">Donee</option>
            <option value="fundraiser">Fundraiser</option>
            <option value="platform_manager">Platform Manager</option>
        </select>
    </div>

    <button type="submit" class="btn-primary">Create Account</button>

</form>

<a href="/index.php?page=admin_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>