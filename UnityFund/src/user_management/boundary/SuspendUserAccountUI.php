<?php

// USER STORY: Suspend User Account
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SuspendUserAccountController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new SuspendUserAccountController();

$userId = (int) ($_GET['userId'] ?? 0);

$controller->suspendAccount($userId);

header('Location: /index.php?page=view_user_accounts');
exit();