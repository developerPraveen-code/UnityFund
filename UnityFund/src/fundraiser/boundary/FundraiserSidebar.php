<?php
// Shared fundraiser sidebar.
// Expects: $user (array from $_SESSION['user']), $activePage (string)
?>
<aside class="sidebar">

    <div>
        <div class="sidebar-logo">
            <img src="/images/unityfund-logo.png" alt="UnityFund Logo" class="sidebar-logo-img">
        </div>

        <nav class="sidebar-menu">

            <a href="/index.php?page=fundraiser_dashboard"
               class="sidebar-link <?= ($activePage === 'overview') ? 'active' : '' ?>">
                ▦ Overview
            </a>

            <a href="/index.php?page=create_fra" class="sidebar-create-btn">
                + New Campaign
            </a>

            <a href="/index.php?page=view_my_fra"
               class="sidebar-link <?= ($activePage === 'my_campaigns') ? 'active' : '' ?>">
                ▣ My Campaigns
            </a>

            <a href="/index.php?page=search_my_fra"
               class="sidebar-link <?= ($activePage === 'search_fra') ? 'active' : '' ?>">
                🔍 Search My FRA
            </a>

            <a href="/index.php?page=make_donation"
               class="sidebar-link <?= ($activePage === 'donate') ? 'active' : '' ?>">
                💛 Make Donation
            </a>

            <a href="/index.php?page=view_posted_views"
               class="sidebar-link <?= ($activePage === 'views') ? 'active' : '' ?>">
                👁 View FRA Views
            </a>

            <a href="/index.php?page=view_shortlist_count"
               class="sidebar-link <?= ($activePage === 'shortlist') ? 'active' : '' ?>">
                ★ View Shortlist Count
            </a>

            <a href="/index.php?page=view_completed_history"
               class="sidebar-link <?= ($activePage === 'history') ? 'active' : '' ?>">
                📜 Completed History
            </a>

            <a href="/index.php?page=search_completed_history"
               class="sidebar-link <?= ($activePage === 'search_history') ? 'active' : '' ?>">
                🔎 Search History
            </a>

            <a href="/index.php?page=logout" class="sidebar-link">
                ⎋ Logout
            </a>

        </nav>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">
            <?= strtoupper(substr($user['name'], 0, 1)) ?>
        </div>

        <div class="user-info">
            <strong><?= htmlspecialchars($user['name']) ?></strong>
            <span>Fundraiser</span>
        </div>
    </div>

</aside>
