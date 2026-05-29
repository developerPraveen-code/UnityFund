<?php

require_once __DIR__ . '/../src/auth/AuthConfig.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => (int) AuthConfig::get('SESSION_TIMEOUT', '3600'),
        'path' => '/',
        'secure' => AuthConfig::bool('SESSION_COOKIE_SECURE', false),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$page = $_GET['page'] ?? 'dashboard';

if ($page === 'dashboard') {
    $role = $_SESSION['user']['role'] ?? null;
    $page = match ($role) {
        'user_admin' => 'admin_dashboard',
        'donee' => 'donee_dashboard',
        'platform_manager' => 'platform_manager_dashboard',
        'fundraiser' => 'fundraiser_dashboard',
        default => 'login',
    };
}

switch ($page) {

    case 'login': require_once __DIR__ . '/../src/login/controller/OidcStartController.php'; break;
    case 'auth_callback': require_once __DIR__ . '/../src/login/controller/OidcCallbackController.php'; break;
    case 'logged_out': require_once __DIR__ . '/../src/login/boundary/LoggedOutUI.php'; break;
    case 'logout': require_once __DIR__ . '/../src/login/boundary/LogoutUI.php'; break;

    case 'admin_dashboard': require_once __DIR__ . '/../src/admin/boundary/AdminDashboard.php'; break;
    case 'donee_dashboard': require_once __DIR__ . '/../src/donee/boundary/DoneeDashboard.php'; break;
    case 'fundraiser_dashboard': require_once __DIR__ . '/../src/fundraiser/boundary/FundraiserDashboard.php'; break;
    case 'platform_manager_dashboard': require_once __DIR__ . '/../src/platform_manager/boundary/PlatformManagerDashboard.php'; break;

    case 'create_fra': require_once __DIR__ . '/../src/fra/boundary/CreateFRAUI.php'; break;
    case 'view_my_fra': require_once __DIR__ . '/../src/fra/boundary/ViewFRAUI.php'; break;
    case 'edit_fra': require_once __DIR__ . '/../src/fra/boundary/EditFRAUI.php'; break;
    case 'disable_fra': require_once __DIR__ . '/../src/fra/boundary/DisableFRAUI.php'; break;
    case 'search_my_fra': require_once __DIR__ . '/../src/fra/boundary/SearchFRAUI.php'; break;
    case 'search_all_fra': require_once __DIR__ . '/../src/fra/boundary/SearchAllFRAUI.php'; break;
    case 'view_fra_details': require_once __DIR__ . '/../src/fra/boundary/ViewFRADetailsUI.php'; break;
    case 'save_favorite': require_once __DIR__ . '/../src/fra/boundary/FavoriteUI.php'; break;
    case 'view_saved_fra': require_once __DIR__ . '/../src/fra/boundary/ViewSavedFRAUI.php'; break;

    case 'search_favorite_fra': require_once __DIR__ . '/../src/fra/boundary/SearchFavouriteListUI.php'; break;
    case 'view_posted_views': require_once __DIR__ . '/../src/fra/boundary/ViewPostedViewsUI.php'; break;
    case 'view_shortlist_count': require_once __DIR__ . '/../src/fra/boundary/ViewShortlistUI.php'; break;
    case 'search_completed_history': require_once __DIR__ . '/../src/fra/boundary/SearchHistoryUI.php'; break;
    case 'view_completed_history': require_once __DIR__ . '/../src/fra/boundary/ViewHistoryUI.php'; break;

    case 'search_donation_history': require_once __DIR__ . '/../src/donation/boundary/SearchDonationHistoryUI.php'; break;
    case 'view_donation_history': require_once __DIR__ . '/../src/donation/boundary/ViewDonationHistoryUI.php'; break;

    case 'view_category': require_once __DIR__ . '/../src/category/boundary/ViewCategoryUI.php'; break;
    case 'create_fra_category': require_once __DIR__ . '/../src/category/boundary/CreateFRACategoryUI.php'; break;

    case 'create_user_profile': require_once __DIR__ . '/../src/user_management/boundary/CreateUserProfileUI.php'; break;
    case 'view_user_profiles': require_once __DIR__ . '/../src/user_management/boundary/ViewUserProfilesUI.php'; break;
    case 'view_user_profile': require_once __DIR__ . '/../src/user_management/boundary/ViewUserProfileUI.php'; break;
    case 'update_user_profile': require_once __DIR__ . '/../src/user_management/boundary/UpdateUserProfileUI.php'; break;
    case 'search_user_profile': require_once __DIR__ . '/../src/user_management/boundary/SearchUserProfileUI.php'; break;
    case 'suspend_user_profile': require_once __DIR__ . '/../src/user_management/boundary/SuspendUserProfileUI.php'; break;

    case 'create_user_account': require_once __DIR__ . '/../src/user_management/boundary/CreateUserAccountUI.php'; break;
    case 'view_user_accounts': require_once __DIR__ . '/../src/user_management/boundary/ViewUserAccountsUI.php'; break;
    case 'search_user_account': require_once __DIR__ . '/../src/user_management/boundary/SearchUserAccountUI.php'; break;
    case 'suspend_user_account': require_once __DIR__ . '/../src/user_management/boundary/SuspendUserAccountUI.php'; break;
    case 'update_user_account': require_once __DIR__ . '/../src/user_management/boundary/UpdateUserAccountUI.php'; break;

    case 'monthly_report': require_once __DIR__ . '/../src/report/boundary/MonthlyReportUI.php'; break;
    case 'daily_report': require_once __DIR__ . '/../src/report/boundary/DailyReportUI.php'; break;
    case 'weekly_report': require_once __DIR__ . '/../src/report/boundary/WeeklyReportUI.php'; break;

    case 'update_fra_category': require_once __DIR__ . '/../src/category/boundary/UpdateFRACategoryUI.php'; break;
    case 'search_category': require_once __DIR__ . '/../src/category/boundary/SearchCategoryUI.php'; break;
    case 'suspend_fra_category': require_once __DIR__ . '/../src/category/boundary/SuspendFRACategoryUI.php'; break;

    default:
        echo "<h1>404 Page Not Found</h1>";
        break;
}
