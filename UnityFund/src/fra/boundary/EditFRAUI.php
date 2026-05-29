<?php

require_once __DIR__ . '/../controller/EditFRAController.php';
require_once __DIR__ . '/../entity/FundraisingActivity.php';

$entity = new FundraisingActivity();
$controller = new EditFRAController();

$fraId = (int) ($_GET['fraId'] ?? 0);

$fra = $entity->getFRA($fraId);

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
    $fra = $entity->getFRA($fraId);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit FRA</title>
<link rel="stylesheet" href="/css/style.css">
</head>

<body>

<div class="page-center">

<section class="dashboard-card">

<h1>Edit FRA</h1>

<?php if ($message): ?>
<div class="success-message">
<?= $message ?>
</div>
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
value="<?= $fra['goalAmount'] ?>" required>
</div>

<div class="form-group">
<label>Status</label>

<select name="status">
<option value="Active">Active</option>
<option value="Disabled">Disabled</option>
</select>

</div>

<button class="btn-primary">
Save Changes
</button>

<a href="/index.php?page=view_my_fra" class="secondary-btn">
Back
</a>

</form>

</section>

</div>

</body>
</html>