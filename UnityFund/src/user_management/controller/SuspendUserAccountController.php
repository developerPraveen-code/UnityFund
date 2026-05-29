<?php

// USER STORY: Suspend User Account
// BCE Role: Controller

require_once __DIR__ . '/../entity/ManagedUserAccount.php';

class SuspendUserAccountController
{
    private ManagedUserAccount $managedUserAccount;

    public function __construct()
    {
        $this->managedUserAccount = new ManagedUserAccount();
    }

    public function suspendAccount(int $userId): string
    {
        return $this->managedUserAccount->suspendAccount($userId);
    }
}