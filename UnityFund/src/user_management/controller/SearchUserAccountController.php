<?php

// USER STORY #10
// BCE Role: Controller

require_once __DIR__ . '/../entity/ManagedUserAccount.php';

class SearchUserAccountController
{
    private ManagedUserAccount $user;

    public function __construct()
    {
        $this->user=new ManagedUserAccount();
    }

    public function searchUserAccount(
        string $username
    ): array
    {
        return $this->user
            ->searchUserAccount(
                $username
            );
    }
}