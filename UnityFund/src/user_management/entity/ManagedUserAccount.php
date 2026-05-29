<?php

// USER STORY #6: Create User Account
// USER STORY #7: View User Accounts
// USER STORY #8: Update User Account
// USER STORY #9: Suspend User Account
// USER STORY #10: Search User Account
// BCE Role: Entity

require_once __DIR__ . '/../../shared/database/Database.php';

class ManagedUserAccount
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // USER STORY #6
    public function createAccount(string $username, string $email, string $role): string
    {
        $permission = $this->defaultPermission($role);

        $sql = "INSERT INTO managed_user_accounts
                    (username,email,role,permission,status)
                VALUES
                    (:username,:email,:role,:permission,'Active')";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':username',$username);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':role',$role);
        $stmt->bindParam(':permission',$permission);

        return $stmt->execute()
            ? 'User account created successfully.'
            : 'User account creation failed.';
    }

    // USER STORY #7
    public function getAllAccounts(): array
    {
        $sql = "SELECT
                    user_id AS \"userId\",
                    username,
                    email,
                    role,
                    permission,
                    status
                FROM managed_user_accounts
                ORDER BY user_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // USER STORY #10
    public function searchUserAccount(string $username): array
    {
        $sql = "SELECT
                    user_id AS \"userId\",
                    username,
                    email,
                    role,
                    permission,
                    status
                FROM managed_user_accounts
                WHERE username LIKE :username";

        $search = "%".$username."%";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':username',$search);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // USER STORY #8
    public function getAccount(int $userId): ?array
    {
        $sql = "SELECT
                    user_id AS \"userId\",
                    username,
                    email,
                    role,
                    permission,
                    status
                FROM managed_user_accounts
                WHERE user_id=:userId";

        $stmt=$this->conn->prepare($sql);

        $stmt->bindParam(
            ':userId',
            $userId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $account=$stmt->fetch(PDO::FETCH_ASSOC);

        return $account ?: null;
    }

    // USER STORY #8
    public function updateUserAccount(
        int $userId,
        string $permission
    ): string
    {
        $sql="UPDATE managed_user_accounts
              SET permission=:permission
              WHERE user_id=:userId";

        $stmt=$this->conn->prepare($sql);

        $stmt->bindParam(
            ':permission',
            $permission
        );

        $stmt->bindParam(
            ':userId',
            $userId,
            PDO::PARAM_INT
        );

        return $stmt->execute()
            ? 'User account updated successfully.'
            : 'Update failed.';
    }

    // USER STORY #9
    public function suspendAccount(
        int $userId
    ): string
    {
        $sql="UPDATE managed_user_accounts
              SET status='Suspended'
              WHERE user_id=:userId";

        $stmt=$this->conn->prepare($sql);

        $stmt->bindParam(
            ':userId',
            $userId,
            PDO::PARAM_INT
        );

        return $stmt->execute()
            ? 'User account suspended successfully.'
            : 'Suspend failed.';
    }

    private function defaultPermission(
        string $role
    ): string
    {
        return match($role){

            'user_admin'=>'Full Access',
            'donee'=>'Donation Access',
            'fundraiser'=>'Campaign Access',
            'platform_manager'=>'Platform Access',

            default=>'Basic Access'
        };
    }
}