<?php

// USER STORY #34: Search Donation History
// BCE Role: Boundary
// Allows Donee to search donation history by FRA, category, status, or date.

require_once __DIR__ . '/../../login/boundary/UserSession.php';
require_once __DIR__ . '/../controller/SearchDonationHistoryController.php';

$userSession = new UserSession();
$userSession->requireLogin();

if ($_SESSION['user']['role'] !== 'donee') {
    header('Location: /index.php?page=login');
    exit();
}

$controller = new SearchDonationHistoryController();
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $results = $controller->searchDonationHistory(
        $_SESSION['user']['id'],
        $_POST['keyword'] ?? ''
    );
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Donation History</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<div class="page-center">
<section class="dashboard-card">

<h1>Search Donation History</h1>

<form method="POST">
    <div class="form-group">
        <label>Keyword</label>
        <input type="text" name="keyword" placeholder="Search by FRA, category, status, or date...">
    </div>

    <button class="btn-primary">Search</button>
</form>

<table class="table">
<tr>
    <th>FRA</th>
    <th>Category</th>
    <th>Amount</th>
    <th>Date</th>
    <th>Status</th>
</tr>

<?php foreach ($results as $donation): ?>
<tr>
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