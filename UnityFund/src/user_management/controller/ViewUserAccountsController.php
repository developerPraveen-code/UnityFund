<?php

// USER STORY: View User Accounts
// BCE Role: Controller

require_once __DIR__ . '/../entity/ManagedUserAccount.php';

class ViewUserAccountsController
{
    private ManagedUserAccount $managedUserAccount;

    public function __construct()
    {
        $this->managedUserAccount = new ManagedUserAccount();
    }

    public function getAllAccounts(): array
    {
        return $this->managedUserAccount->getAllAccounts();
    }
}