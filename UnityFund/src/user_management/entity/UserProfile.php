<?php

// USER STORY: Create/View/Update User Profile
// USER STORY #4: Suspend User Profile
// USER STORY #5: Search User Profiles
// BCE Role: Entity
// Entity only communicates with database. No $_SESSION is used here.

require_once __DIR__ . '/../../shared/database/Database.php';

class UserProfile
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createProfile(string $fullName, string $phone, string $address, string $role): string
    {
        try {
            $this->conn->beginTransaction();

            $username = $fullName;
            $email = strtolower(str_replace(' ', '', $fullName)) . rand(100, 999) . '@unityfund.local';
            $permission = $this->defaultPermission($role);

            $accountSql = "INSERT INTO managed_user_accounts
                                (username, email, password_hash, role, permission, status)
                           VALUES
                                (:username, :email, 'default_password_hash', :role, :permission, 'Active')";

            $accountStmt = $this->conn->prepare($accountSql);
            $accountStmt->bindParam(':username', $username);
            $accountStmt->bindParam(':email', $email);
            $accountStmt->bindParam(':role', $role);
            $accountStmt->bindParam(':permission', $permission);
            $accountStmt->execute();

            $userId = (int) $this->conn->lastInsertId();

            $profileSql = "INSERT INTO user_profiles
                                (user_id, full_name, phone, address, role, status)
                           VALUES
                                (:userId, :fullName, :phone, :address, :role, 'Active')";

            $profileStmt = $this->conn->prepare($profileSql);
            $profileStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $profileStmt->bindParam(':fullName', $fullName);
            $profileStmt->bindParam(':phone', $phone);
            $profileStmt->bindParam(':address', $address);
            $profileStmt->bindParam(':role', $role);
            $profileStmt->execute();

            $this->conn->commit();

            return 'User profile created successfully.';

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return 'User profile creation failed: ' . $e->getMessage();
        }
    }

    public function getAllProfiles(): array
    {
        $sql = "SELECT
                    profile_id AS \"profileId\",
                    user_id AS \"userId\",
                    full_name AS \"fullName\",
                    phone,
                    address,
                    role,
                    status
                FROM user_profiles
                ORDER BY profile_id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProfile(int $profileId): ?array
    {
        $sql = "SELECT
                    profile_id AS \"profileId\",
                    user_id AS \"userId\",
                    full_name AS \"fullName\",
                    phone,
                    address,
                    role,
                    status
                FROM user_profiles
                WHERE profile_id = :profileId
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();

        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        return $profile ?: null;
    }

    public function updateProfile(int $profileId, string $fullName, string $phone, string $address, string $role): string
    {
        $sql = "UPDATE user_profiles
                SET full_name = :fullName,
                    phone = :phone,
                    address = :address,
                    role = :role
                WHERE profile_id = :profileId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fullName', $fullName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':profileId', $profileId, PDO::PARAM_INT);

        return $stmt->execute()
            ? 'User profile updated successfully.'
            : 'User profile not found.';
    }

    // USER STORY #4: Suspend User Profile
    public function suspendProfile(int $profileId): string
    {
        $sql = "UPDATE user_profiles
                SET status = 'Suspended'
                WHERE profile_id = :profileId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':profileId', $profileId, PDO::PARAM_INT);

        return $stmt->execute()
            ? 'User profile suspended successfully.'
            : 'User profile not found.';
    }

    // USER STORY #5: Search User Profiles
    public function searchUserProfile(string $searchTerm): array
    {
        $sql = "SELECT
                    profile_id AS \"profileId\",
                    user_id AS \"userId\",
                    full_name AS \"fullName\",
                    phone,
                    address,
                    role,
                    status
                FROM user_profiles
                WHERE full_name LIKE :searchTerm
                   OR profile_id LIKE :searchTerm
                ORDER BY profile_id ASC";

        $keyword = '%' . $searchTerm . '%';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':searchTerm', $keyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function defaultPermission(string $role): string
    {
        return match ($role) {
            'user_admin' => 'Full Access',
            'donee' => 'Donation Access',
            'fundraiser' => 'Campaign Access',
            'platform_manager' => 'Platform Access',
            default => 'Basic Access'
        };
    }
}
