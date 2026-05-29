<?php

// USER STORY: Create User Profile
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/CreateUserProfileController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new CreateUserProfileController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $controller->createProfile(
        trim($_POST['fullName']),
        trim($_POST['phone']),
        trim($_POST['address']),
        trim($_POST['role'])
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create User Profile</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Create User Profile</h1>

<?php if ($message): ?>
<div class="success-message">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="fullName" required>
    </div>

    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" required>
    </div>

    <div class="form-group">
        <label>Address</label>
        <input type="text" name="address" required>
    </div>

    <div class="form-group">
        <label>Role</label>

        <select name="role" required>
            <option value="donee">Donee</option>
            <option value="fundraiser">Fundraiser</option>
            <option value="platform_manager">Platform Manager</option>
        </select>
    </div>

    <button type="submit" class="btn-primary">
        Create Profile
    </button>

</form>

<a href="/index.php?page=admin_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>