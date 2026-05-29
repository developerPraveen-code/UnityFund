<?php

// USER STORY: View Specific User Profile
// USER STORY #4: Suspend User Profile
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewUserProfileController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new ViewUserProfileController();

$profileId = (int) ($_GET['profileId'] ?? 0);

$profile = $controller->getProfile($profileId);

if (!$profile) {
    die('Profile not found.');
}

$message = $_GET['message'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>View User Profile</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>User Profile Details</h1>

<?php if ($message): ?>
<div class="success-message">
    <?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<p><strong>ID:</strong> <?= htmlspecialchars($profile['profileId']) ?></p>
<p><strong>Name:</strong> <?= htmlspecialchars($profile['fullName']) ?></p>
<p><strong>Phone:</strong> <?= htmlspecialchars($profile['phone']) ?></p>
<p><strong>Address:</strong> <?= htmlspecialchars($profile['address']) ?></p>
<p><strong>Role:</strong> <?= htmlspecialchars($profile['role']) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($profile['status']) ?></p>

<a href="/index.php?page=update_user_profile&profileId=<?= $profile['profileId'] ?>"
   class="btn-primary"
   style="display:block;text-align:center;margin-top:20px;padding:14px;text-decoration:none;">
   Update Profile
</a>

<?php if ($profile['status'] !== 'Suspended'): ?>
<a href="/index.php?page=suspend_user_profile&profileId=<?= $profile['profileId'] ?>"
   class="secondary-btn"
   style="display:block;text-align:center;margin-top:15px;padding:14px;text-decoration:none;"
   onclick="return confirm('Are you sure you want to suspend this user profile?');">
   Suspend Profile
</a>
<?php endif; ?>

<a href="/index.php?page=view_user_profiles" class="secondary-btn">
    Back
</a>

</section>
</div>

</body>
</html>