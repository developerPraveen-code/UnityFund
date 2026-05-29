<?php

// USER STORY #22: Save FRA to Favorites
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/FavoriteController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'donee') {
    header('Location: /index.php?page=login');
    exit();
}

$fraId = (int) ($_GET['fraId'] ?? 0);
$userId = (int) $_SESSION['user']['id'];

$controller = new FavoriteController();
$saved = $controller->saveFavorite($userId, $fraId);

if ($saved) {
    header('Location: /index.php?page=search_all_fra&message=saved');
    exit();
}

header('Location: /index.php?page=search_all_fra&message=already_saved');
exit();