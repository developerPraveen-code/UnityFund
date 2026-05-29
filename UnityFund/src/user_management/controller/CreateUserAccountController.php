<?php

// USER STORY: Create User Account
// BCE Role: Controller

require_once __DIR__ . '/../entity/ManagedUserAccount.php';

class CreateUserAccountController
{
    private ManagedUserAccount $managedUserAccount;

    public function __construct()
    {
        $this->managedUserAccount = new ManagedUserAccount();
    }

    public function createAccount(string $username, string $email, string $role): string
    {
        return $this->managedUserAccount->createAccount($username, $email, $role);
    }
}