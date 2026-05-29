<?php

// USER STORY #8: Update User Account
// BCE Role: Controller

require_once __DIR__ . '/../entity/ManagedUserAccount.php';

class UpdateUserAccountController
{
    private ManagedUserAccount $managedUserAccount;

    public function __construct()
    {
        $this->managedUserAccount = new ManagedUserAccount();
    }

    public function getAccount(int $userId): ?array
    {
        return $this->managedUserAccount->getAccount($userId);
    }

    public function updateUserAccount(int $userId, string $permission): string
    {
        return $this->managedUserAccount->updateUserAccount($userId, $permission);
    }
}