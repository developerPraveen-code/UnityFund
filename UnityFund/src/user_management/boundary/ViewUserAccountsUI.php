<?php

// USER STORY #7: View User Accounts
// USER STORY #8: Update User Account
// USER STORY #9: Suspend User Account
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewUserAccountsController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'user_admin') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new ViewUserAccountsController();
$accounts = $controller->getAllAccounts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View User Accounts</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>User Accounts</h1>

<table class="table">
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Role</th>
    <th>Permission</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php foreach ($accounts as $account): ?>
<tr>
    <td><?= $account['userId'] ?></td>
    <td><?= htmlspecialchars($account['username']) ?></td>
    <td><?= htmlspecialchars($account['email']) ?></td>
    <td><?= htmlspecialchars($account['role']) ?></td>
    <td><?= htmlspecialchars($account['permission'] ?? 'Basic Access') ?></td>
    <td><?= htmlspecialchars($account['status']) ?></td>
    <td>
        <a class="action-link"
           href="/index.php?page=update_user_account&userId=<?= $account['userId'] ?>">
            Update
        </a>

        |

        <?php if ($account['status'] !== 'Suspended'): ?>
            <a class="action-link"
               href="/index.php?page=suspend_user_account&userId=<?= $account['userId'] ?>">
                Suspend
            </a>
        <?php else: ?>
            Suspended
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<a href="/index.php?page=admin_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>