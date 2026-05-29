<?php

// USER STORY #44: Generate Daily Report
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/DailyReportController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'platform_manager') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new DailyReportController();
$report = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report = $controller->generateDailyReport($_POST['selectedDate']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Report</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Generate Daily Report</h1>

<form method="POST">
    <div class="form-group">
        <label>Select Date</label>
        <input type="date" name="selectedDate" required>
    </div>

    <button type="submit" class="btn-primary">Generate Daily Report</button>
</form>

<?php if ($report): ?>
<table class="table">
<tr>
    <th>Date</th>
    <td><?= htmlspecialchars($report['selectedDate']) ?></td>
</tr>
<tr>
    <th>Total Funds Raised</th>
    <td>$<?= number_format($report['totalFundsRaised'], 2) ?></td>
</tr>
<tr>
    <th>Total Donations</th>
    <td><?= $report['totalDonations'] ?></td>
</tr>
<tr>
    <th>Total Transactions</th>
    <td><?= $report['totalTransactions'] ?></td>
</tr>
<tr>
    <th>Completed FRA</th>
    <td><?= $report['completedFRA'] ?></td>
</tr>
</table>
<?php endif; ?>

<a href="/index.php?page=platform_manager_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>