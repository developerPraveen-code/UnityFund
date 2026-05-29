<?php

// USER STORY #35: View Donation History
// BCE Role: Boundary
// Displays all donation history records for the logged-in donee.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/ViewDonationHistoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'donee') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new ViewDonationHistoryController();
$donationHistory = $controller->getDonationHistory($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donation History</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Donation History</h1>

<table class="table">
<tr>
    <th>ID</th>
    <th>FRA</th>
    <th>Category</th>
    <th>Amount</th>
    <th>Date</th>
    <th>Status</th>
</tr>

<?php foreach ($donationHistory as $donation): ?>
<tr>
    <td><?= $donation['donationId'] ?></td>
    <td><?= htmlspecialchars($donation['fraTitle']) ?></td>
    <td><?= htmlspecialchars($donation['category']) ?></td>
    <td>$<?= number_format($donation['amount'], 2) ?></td>
    <td><?= htmlspecialchars($donation['donationDate']) ?></td>
    <td><?= htmlspecialchars($donation['status']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<a href="/index.php?page=donee_dashboard" class="secondary-btn">Back</a>

</section>
</div>

</body>
</html>