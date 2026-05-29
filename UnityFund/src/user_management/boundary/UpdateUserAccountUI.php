<?php

// USER STORY #8: Update User Account
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/UpdateUserAccountController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new UpdateUserAccountController();

$userId = (int) ($_GET['userId'] ?? 0);
$account = $controller->getAccount($userId);

if (!$account) {
    die('User account not found.');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $controller->updateUserAccount(
        $userId,
        trim($_POST['permission'])
    );

    $account = $controller->getAccount($userId);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User Account</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Update User Account</h1>

<?php if ($message): ?>
<div class="success-message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<p><strong>Username:</strong> <?= htmlspecialchars($account['username']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($account['email']) ?></p>
<p><strong>Role:</strong> <?= htmlspecialchars($account['role']) ?></p>

<form method="POST">

    <div class="form-group">
        <label>Permission</label>
        <select name="permission" required>
            <option value="Full Access" <?= $account['permission'] === 'Full Access' ? 'selected' : '' ?>>Full Access</option>
            <option value="Platform Access" <?= $account['permission'] === 'Platform Access' ? 'selected' : '' ?>>Platform Access</option>
            <option value="Campaign Access" <?= $account['permission'] === 'Campaign Access' ? 'selected' : '' ?>>Campaign Access</option>
            <option value="Donation Access" <?= $account['permission'] === 'Donation Access' ? 'selected' : '' ?>>Donation Access</option>
            <option value="Basic Access" <?= $account['permission'] === 'Basic Access' ? 'selected' : '' ?>>Basic Access</option>
        </select>
    </div>

    <button type="submit" class="btn-primary">Update Account</button>

</form>

<a href="/index.php?page=view_user_accounts" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>