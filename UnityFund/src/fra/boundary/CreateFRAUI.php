<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/CreateFRAController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$user = $_SESSION['user'];
$controller = new CreateFRAController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $controller->createFRA(
        $user['id'],
        $_POST['title'],
        $_POST['description'],
        (float) $_POST['goalAmount'],
        $_POST['category']
    );
}

$activePage = 'create_fra';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Campaign</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="app-layout">

    <?php require_once __DIR__ . '/../../fundraiser/boundary/FundraiserSidebar.php'; ?>

    <main class="main-content">

        <header class="topbar">
            <div class="topbar-left">
                <div class="menu-icon">☰</div>
                <div class="topbar-title">
                    <h2>Create Campaign</h2>
                    <p>Fundraiser</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg" style="display:flex;justify-content:center;padding-top:32px">

            <section class="dashboard-card">

                <h1>Create Fundraising Activity</h1>

                <?php if ($message): ?>
                <div class="success-message">
                    <?= htmlspecialchars($message) ?>
                </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Goal Amount</label>
                        <input type="number" name="goalAmount" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" required>
                    </div>

                    <button class="btn-primary" type="submit">Create FRA</button>

                </form>

            </section>

        </section>

    </main>

</div>

</body>
</html>
