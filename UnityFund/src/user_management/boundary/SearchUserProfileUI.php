<?php

// USER STORY #5: Search User Profiles
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SearchUserProfileController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new SearchUserProfileController();

$searchTerm = $_POST['searchTerm'] ?? '';
$profiles = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profiles = $controller->searchUserProfile($searchTerm);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search User Profiles</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Search User Profiles</h1>

<form method="POST">

<div class="form-group">
<label>Search by Name or ID</label>
<input type="text" name="searchTerm" value="<?= htmlspecialchars($searchTerm) ?>" required>
</div>

<button class="btn-primary" type="submit">
Search
</button>

</form>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>

<?php if (empty($profiles)): ?>

<p>No matching user profiles found.</p>

<?php else: ?>

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

<?php endif; ?>

<?php endif; ?>

<a href="/index.php?page=admin_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>