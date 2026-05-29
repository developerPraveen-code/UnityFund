<?php

// USER STORY #45: Generate Weekly Report
// BCE Role: Boundary

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/WeeklyReportController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'platform_manager') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new WeeklyReportController();
$report = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report = $controller->generateWeeklyReport(
        $_POST['startDate'],
        $_POST['endDate']
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Weekly Report</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Generate Weekly Report</h1>

<form method="POST">

    <div class="form-group">
        <label>Start Date</label>
        <input type="date" name="startDate" required>
    </div>

    <div class="form-group">
        <label>End Date</label>
        <input type="date" name="endDate" required>
    </div>

    <button type="submit" class="btn-primary">Generate Weekly Report</button>

</form>

<?php if ($report): ?>
<table class="table">
<tr>
    <th>Start Date</th>
    <td><?= htmlspecialchars($report['startDate']) ?></td>
</tr>
<tr>
    <th>End Date</th>
    <td><?= htmlspecialchars($report['endDate']) ?></td>
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