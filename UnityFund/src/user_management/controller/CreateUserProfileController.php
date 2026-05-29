<?php

// USER STORY: Create User Profile
// BCE Role: Controller

require_once __DIR__ . '/../entity/UserProfile.php';

class CreateUserProfileController
{
    private UserProfile $userProfile;

    public function __construct()
    {
        $this->userProfile = new UserProfile();
    }

    public function createProfile(string $fullName, string $phone, string $address, string $role): string
    {
        return $this->userProfile->createProfile($fullName, $phone, $address, $role);
    }
}