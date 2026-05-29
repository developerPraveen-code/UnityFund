<?php

// USER STORY: Update User Profile
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewUserProfileController.php';
require_once __DIR__ . '/../controller/UpdateUserProfileController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$viewController = new ViewUserProfileController();
$updateController = new UpdateUserProfileController();

$profileId = (int) ($_GET['profileId'] ?? 0);

$profile = $viewController->getProfile($profileId);

if (!$profile) {
    die('Profile not found.');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = $updateController->updateProfile(
        $profileId,
        trim($_POST['fullName']),
        trim($_POST['phone']),
        trim($_POST['address']),
        trim($_POST['role'])
    );

    $profile = $viewController->getProfile($profileId);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User Profile</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Update User Profile</h1>

<?php if ($message): ?>
<div class="success-message">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="fullName"
               value="<?= htmlspecialchars($profile['fullName']) ?>" required>
    </div>

    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone"
               value="<?= htmlspecialchars($profile['phone']) ?>" required>
    </div>

    <div class="form-group">
        <label>Address</label>
        <input type="text" name="address"
               value="<?= htmlspecialchars($profile['address']) ?>" required>
    </div>

    <div class="form-group">
        <label>Role</label>

        <select name="role">

            <option value="donee"
                <?= $profile['role'] === 'donee' ? 'selected' : '' ?>>
                Donee
            </option>

            <option value="fundraiser"
                <?= $profile['role'] === 'fundraiser' ? 'selected' : '' ?>>
                Fundraiser
            </option>

            <option value="platform_manager"
                <?= $profile['role'] === 'platform_manager' ? 'selected' : '' ?>>
                Platform Manager
            </option>

        </select>
    </div>

    <button type="submit" class="btn-primary">
        Update Profile
    </button>

</form>

<a href="/index.php?page=view_user_profiles" class="secondary-btn">
    Back
</a>

</section>
</div>

</body>
</html>