# UnityFund Product Requirements Document

## 1. Product Overview

UnityFund is a PHP-based fundraising activity platform that connects fundraisers, donees, user administrators, and platform managers. The system supports creating and managing fundraising activities, saving favourite activities, viewing donation history, managing user accounts and profiles, maintaining fundraising categories, and generating platform reports.

The current application is structured using a Boundary-Control-Entity pattern and runs as a server-rendered PHP web app with a MySQL database backend.

## 2. Product Goals

- Provide a simple role-based platform for fundraising activity management.
- Allow fundraisers to create, update, disable, search, and monitor fundraising activities.
- Allow donees to discover fundraising activities, save favourites, and view donation history.
- Allow user administrators to manage platform user accounts and profiles.
- Allow platform managers to maintain fundraising categories and view operational reports.
- Maintain a clean database-backed architecture with clear separation between UI, controller, and entity logic.

## 3. Target Users

### User Administrator

Manages platform users and profile records. This user needs tools to create accounts, create profiles, search users, update records, and suspend accounts or profiles.

### Fundraiser

Creates and manages fundraising activities. This user needs visibility into campaign progress, views, shortlist counts, and completed fundraising history.

### Donee

Browses fundraising activities and tracks personal interactions with them. This user needs search, favourite/saved activity management, and donation history access.

### Platform Manager

Maintains platform-level fundraising categories and reviews operational reports. This user needs category management and daily, weekly, and monthly reporting.

## 4. Scope

### In Scope

- Role-based login and dashboard routing.
- User account management.
- User profile management.
- Fundraising activity creation, editing, viewing, disabling, searching, and history viewing.
- Favourite fundraising activity saving, viewing, and searching.
- Donation history viewing and searching.
- Fundraising category creation, viewing, updating, searching, and suspension.
- Daily, weekly, and monthly reporting.
- MySQL schema for users, profiles, categories, fundraising activities, favourites, donations, and reports.

### Out of Scope For Current Version

- Real payment processing.
- Public self-registration.
- Email verification and password reset.
- Real password hashing and verification in production-ready form.
- Notification system.
- Approval workflow for new fundraising activities.
- File uploads for campaign images or documents.
- Advanced analytics dashboards.

## 5. User Roles And Permissions

| Role | Primary Access |
| --- | --- |
| `user_admin` | Manage user accounts and user profiles |
| `fundraiser` | Manage own fundraising activities and activity performance |
| `donee` | Search fundraising activities, save favourites, view donation history |
| `platform_manager` | Manage categories and generate reports |

Users must only access pages and workflows intended for their role. Dashboards currently enforce role checks after login.

## 6. Functional Requirements

### 6.1 Authentication

- The system shall provide a login page for all supported roles.
- The system shall create a user session after successful login.
- The system shall redirect users to the correct dashboard based on role.
- The system shall prevent logged-in users from accessing dashboards for other roles.
- The system shall provide logout functionality that clears the session.

### 6.2 User Account Management

- User administrators shall be able to create user accounts.
- User administrators shall be able to view all user accounts.
- User administrators shall be able to search user accounts by username.
- User administrators shall be able to update account permissions.
- User administrators shall be able to suspend user accounts.
- Each account shall include username, email, role, permission, status, and creation date.

### 6.3 User Profile Management

- User administrators shall be able to create user profiles.
- Creating a profile shall also create a linked managed user account.
- User administrators shall be able to view all profiles.
- User administrators shall be able to view profile details.
- User administrators shall be able to update profile information.
- User administrators shall be able to search profiles by name or profile ID.
- User administrators shall be able to suspend profiles.

### 6.4 Fundraising Activity Management

- Fundraisers shall be able to create fundraising activities.
- Each fundraising activity shall include title, description, goal amount, current amount, category, status, start date, end date, view count, and shortlist count.
- Fundraisers shall be able to view their own fundraising activities.
- Fundraisers shall be able to edit existing fundraising activities.
- Fundraisers shall be able to disable fundraising activities.
- Fundraisers shall be able to search their own fundraising activities by title, description, or category.
- Fundraisers shall be able to view completed fundraising activity history.
- Fundraisers shall be able to search completed fundraising activity history.
- Fundraisers shall be able to view activity view counts.
- Fundraisers shall be able to view shortlist counts.

### 6.5 Fundraising Activity Discovery

- Donees shall be able to search active fundraising activities.
- Donees shall be able to view fundraising activity details.
- Viewing fundraising activity details shall increment the activity view count.
- Donees shall be able to save fundraising activities to a favourite list.
- Saving a fundraising activity shall increment the activity shortlist count.
- The system shall prevent duplicate favourite records for the same user and fundraising activity.
- Donees shall be able to view saved fundraising activities.
- Donees shall be able to search saved fundraising activities.

### 6.6 Donation History

- Donees shall be able to view their donation history.
- Donees shall be able to search donation history by fundraising activity title, category, status, or date.
- Donation records shall include donation ID, donee ID, fundraising activity title, category, amount, donation date, and status.

### 6.7 Category Management

- Platform managers shall be able to create fundraising activity categories.
- Platform managers shall be able to view all categories.
- Platform managers shall be able to update category name and description.
- Platform managers shall be able to search categories by name, description, or status.
- Platform managers shall be able to suspend categories.
- Categories shall include category ID, category name, description, status, and creation date.

### 6.8 Reporting

- Platform managers shall be able to generate daily reports.
- Platform managers shall be able to generate weekly reports.
- Platform managers shall be able to generate monthly reports.
- Reports shall summarize funds raised, donation counts, transaction counts where applicable, completed fundraising activities, and average donation where applicable.

## 7. Data Requirements

The system shall use a MySQL database with the following primary tables:

- `managed_user_accounts`
- `user_profiles`
- `fra_categories`
- `fundraising_activities`
- `favorites`
- `donations`
- `daily_reports`
- `weekly_reports`
- `monthly_reports`

Key relationships:

- A user account can have a linked user profile.
- A fundraiser user account can own multiple fundraising activities.
- A donee user account can save multiple fundraising activities as favourites.
- A donee user account can have multiple donation history records.
- Fundraising activities belong to a category by category name.

## 8. Nonfunctional Requirements

### Security

- Database credentials shall be loaded from environment configuration.
- Real secrets must not be committed to source control.
- Role checks shall protect role-specific pages.
- Database access shall use prepared statements.
- User input shall be escaped when rendered in HTML.
- Password handling should use `password_hash()` and `password_verify()` before production deployment.

### Reliability

- Database operations should return clear success or failure messages.
- Transactional workflows, such as profile creation with account creation, should roll back on failure.
- Duplicate favourite entries should be prevented through database constraints and application checks.

### Maintainability

- The system shall continue to follow the Boundary-Control-Entity structure.
- Shared infrastructure such as database connection logic shall remain centralized.
- New features should include focused tests where business behavior can be verified.
- Code should follow PSR-12 style where practical.

### Performance

- Search features should use indexed fields where data volume grows.
- Dashboard counters should eventually be calculated from database queries rather than hardcoded or session-only values.
- Reporting queries should remain efficient for date-based filtering.

## 9. User Experience Requirements

- Each role shall have a dedicated dashboard with relevant navigation.
- Navigation shall expose only workflows relevant to the active role.
- Forms shall provide clear success and error feedback.
- Search results shall be easy to scan and include core identifying fields.
- Status values such as Active, Suspended, Disabled, and Completed shall be visible where relevant.

## 10. Current Known Limitations

- Login verification currently accepts non-empty credentials and maps users by selected role; production authentication is not fully implemented.
- Some dashboard metrics are hardcoded or session-based instead of fully database-backed.
- Donation history exists, but real donation/payment creation is not in scope in the current codebase.
- Fundraising activity categories are stored on activities as category text rather than a strict foreign key.
- The security guide references improvements that may not all be fully reflected in the current implementation.

## 11. Success Metrics

- Users can log in and reach the correct dashboard for each role.
- User administrators can complete account and profile management workflows.
- Fundraisers can create, manage, search, and review fundraising activities.
- Donees can discover, save, and search fundraising activities.
- Donees can view and search donation history.
- Platform managers can manage categories and generate reports.
- Sensitive credentials are not present in committed example files.
- Core workflows are covered by automated tests.

## 12. Future Enhancements

- Implement production-grade authentication with hashed passwords.
- Add password reset and email verification.
- Add donation creation and payment gateway integration.
- Add campaign approval, moderation, and audit logs.
- Add uploaded media for fundraising activities.
- Add richer analytics for fundraisers and platform managers.
- Add notification support for campaign updates and donation events.
- Normalize fundraising categories with foreign key relationships.
- Improve dashboard statistics with real-time database queries.
- Add pagination and sorting for large lists.

## 13. Release Criteria

- All role dashboards are accessible only to authorized roles.
- CRUD and search workflows for users, profiles, fundraising activities, and categories function against the database.
- Donation history and report pages return accurate data from the database.
- Example environment configuration contains placeholders only.
- Automated tests pass.
- Static analysis and linting issues are reviewed or resolved.
