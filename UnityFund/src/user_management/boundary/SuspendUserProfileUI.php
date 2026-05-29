<?php

// USER STORY #4: Suspend User Profile
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SuspendUserProfileController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$profileId = (int) ($_GET['profileId'] ?? 0);

$controller = new SuspendUserProfileController();
$message = $controller->suspendProfile($profileId);

header('Location: /index.php?page=view_user_profile&profileId=' . $profileId . '&message=' . urlencode($message));
exit();