<?php

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/MonthlyReportController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'platform_manager') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new MonthlyReportController();

$report = null;
$selectedMonth = $_POST['month'] ?? date('m');
$selectedYear = $_POST['year'] ?? date('Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report = $controller->generateReport((int)$selectedMonth, (int)$selectedYear);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Generate Monthly Report</title>
<link rel="stylesheet" href="/css/style.css">
</head>

<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Generate Monthly Report</h1>

<form method="POST">

<div class="form-group">
<label>Select Month</label>
<select name="month" required>
<?php for ($m = 1; $m <= 12; $m++): ?>
<option value="<?= $m ?>" <?= ((int)$selectedMonth === $m) ? 'selected' : '' ?>>
<?= date('F', mktime(0, 0, 0, $m, 1)) ?>
</option>
<?php endfor; ?>
</select>
</div>

<div class="form-group">
<label>Select Year</label>
<input type="number" name="year" value="<?= htmlspecialchars($selectedYear) ?>" required>
</div>

<button class="btn-primary" type="submit">
Generate Monthly Report
</button>

</form>

<?php if ($report !== null): ?>

<h2 style="margin-top:30px;">Monthly Report</h2>

<table class="table">
<tr><th>Month</th><td><?= htmlspecialchars($report['month']) ?></td></tr>
<tr><th>Year</th><td><?= htmlspecialchars($report['year']) ?></td></tr>
<tr><th>Total Funds Raised</th><td>$<?= number_format((float)$report['totalFundsRaised'], 2) ?></td></tr>
<tr><th>Total Donations</th><td><?= htmlspecialchars($report['totalDonations']) ?></td></tr>
<tr><th>Completed FRA</th><td><?= htmlspecialchars($report['completedFRA']) ?></td></tr>
<tr><th>Average Donation</th><td>$<?= number_format((float)$report['averageDonation'], 2) ?></td></tr>
</table>

<?php endif; ?>

<a href="/index.php?page=platform_manager_dashboard" class="secondary-btn">
Back
</a>

</section>
</div>

</body>
</html>