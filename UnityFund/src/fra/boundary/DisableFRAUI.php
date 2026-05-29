<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/DisableFRAController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new DisableFRAController();
$fraId = (int) ($_GET['fraId'] ?? 0);
$controller->disableFRA($fraId);

header('Location: /index.php?page=view_my_fra');
exit();
