<?php

// USER STORY: View User Profiles
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewUserProfilesController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new ViewUserProfilesController();
$profiles = $controller->getAllProfiles();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View User Profiles</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>User Profiles</h1>

<table class="table">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Role</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach ($profiles as $profile): ?>

<tr>
    <td><?= htmlspecialchars($profile['profileId']) ?></td>
    <td><?= htmlspecialchars($profile['fullName']) ?></td>
    <td><?= htmlspecialchars($profile['phone']) ?></td>
    <td><?= htmlspecialchars($profile['role']) ?></td>
    <td><?= htmlspecialchars($profile['status']) ?></td>

    <td>
        <a class="action-link"
           href="/index.php?page=view_user_profile&profileId=<?= $profile['profileId'] ?>">
           View
        </a>
    </td>
</tr>

<?php endforeach; ?>

</table>

<a href="/index.php?page=search_user_profile" class="btn-primary" style="display:block;text-align:center;margin-top:20px;text-decoration:none;">
Search User Profiles
</a>

<a href="/index.php?page=admin_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>