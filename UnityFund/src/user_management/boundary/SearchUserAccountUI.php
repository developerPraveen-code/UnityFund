<?php

// USER STORY #10
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SearchUserAccountController.php';

$userSession=new UserSession();
$userSession->requireLogin();

if($_SESSION['user']['role']!=='user_admin')
{
    header(
        'Location:/index.php?page=login'
    );
    exit();
}

$controller=
new SearchUserAccountController();

$username=
$_POST['username'] ?? '';

$userAccountList=[];

if(
$_SERVER['REQUEST_METHOD']==='POST'
)
{
$userAccountList=
$controller
->searchUserAccount(
$username
);
}

?>

<!DOCTYPE html>
<html>

<head>

<title>
Search User Account
</title>

<link
rel="stylesheet"
href="/css/style.css">

</head>

<body>

<div class="page-center">

<section class="dashboard-card">

<h1>
Search User Account
</h1>

<form method="POST">

<div class="form-group">

<label>
Username
</label>

<input
type="text"
name="username"
value="<?=htmlspecialchars($username)?>">

</div>

<button
class="btn-primary">

Search

</button>

</form>

<?php if(!empty($userAccountList)):?>

<table class="table">

<tr>

<th>ID</th>
<th>Username</th>
<th>Email</th>
<th>Role</th>
<th>Status</th>

</tr>

<?php foreach($userAccountList as $user):?>

<tr>

<td><?=htmlspecialchars($user['userId'])?></td>

<td><?=htmlspecialchars($user['username'])?></td>

<td><?=htmlspecialchars($user['email'])?></td>

<td><?=htmlspecialchars($user['role'])?></td>

<td><?=htmlspecialchars($user['status'])?></td>

</tr>

<?php endforeach;?>

</table>

<?php endif;?>

<a
href="/index.php?page=admin_dashboard"
class="secondary-btn">

Back

</a>

</section>
</div>
</body>
</html>