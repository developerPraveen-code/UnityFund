<?php

// USER STORY: Update User Profile
// BCE Role: Controller

require_once __DIR__ . '/../entity/UserProfile.php';

class UpdateUserProfileController
{
    private UserProfile $userProfile;

    public function __construct()
    {
        $this->userProfile = new UserProfile();
    }

    public function updateProfile(int $profileId, string $fullName, string $phone, string $address, string $role): string
    {
        return $this->userProfile->updateProfile($profileId, $fullName, $phone, $address, $role);
    }
}