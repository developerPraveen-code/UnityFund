require_once __DIR__ . '/../../login/boundary/UserSession.php';

$userSession = new UserSession();
$userSession->requireLogin();


<?php

require_once __DIR__ . '/../controller/DisableFRAController.php';

$controller = new DisableFRAController();

$fraId = (int) ($_GET['fraId'] ?? 0);

$controller->disableFRA($fraId);

header('Location: /index.php?page=view_my_fra');
exit();
