<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/EditFRAController.php';
require_once __DIR__ . '/../entity/FundraisingActivity.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'fundraiser') {
    header('Location: /index.php?page=login');
    exit();
}

$user = $_SESSION['user'];
$entity = new FundraisingActivity();
$controller = new EditFRAController();

$fraId = (int) ($_GET['fraId'] ?? 0);
$fra = $entity->getFRAById($fraId);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->updateFRA(
        $fraId,
        $_POST['title'],
        $_POST['description'],
        (float) $_POST['goalAmount'],
        $_POST['status']
    );
    $message = 'Fundraising activity updated successfully.';
    $fra = $entity->getFRAById($fraId);
}

$activePage = 'my_campaigns';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Campaign</title>
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
                    <h2>Edit Campaign</h2>
                    <p>Fundraiser</p>
                </div>
            </div>
        </header>

        <section class="dashboard-bg" style="display:flex;justify-content:center;padding-top:32px">

            <section class="dashboard-card">

                <h1>Edit FRA</h1>

                <?php if ($message): ?>
                <div class="success-message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title"
                               value="<?= htmlspecialchars($fra['title']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" required><?= htmlspecialchars($fra['description']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Goal Amount</label>
                        <input type="number" name="goalAmount"
                               value="<?= $fra['goalAmount'] ?>" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="Active" <?= $fra['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Disabled" <?= $fra['status'] === 'Disabled' ? 'selected' : '' ?>>Disabled</option>
                        </select>
                    </div>

                    <button class="btn-primary" type="submit">Save Changes</button>

                    <a href="/index.php?page=view_my_fra" class="secondary-btn">Back</a>

                </form>

            </section>

        </section>

    </main>

</div>

</body>
</html>
