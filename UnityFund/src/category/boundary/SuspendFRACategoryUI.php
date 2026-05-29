<?php

// USER STORY #39: Suspend FRA Category
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SuspendFRACategoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'platform_manager') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new SuspendFRACategoryController();

$categoryId = (int) ($_GET['categoryId'] ?? 0);

$controller->suspendCategory($categoryId);

header('Location: /index.php?page=view_category');
exit();