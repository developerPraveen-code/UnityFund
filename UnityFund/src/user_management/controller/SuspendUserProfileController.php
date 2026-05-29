<?php

// USER STORY #4: Suspend User Profile
// BCE Role: Controller

require_once __DIR__ . '/../entity/UserProfile.php';

class SuspendUserProfileController
{
    private UserProfile $userProfile;

    public function __construct()
    {
        $this->userProfile = new UserProfile();
    }

    public function suspendProfile(int $profileId): string
    {
        return $this->userProfile->suspendProfile($profileId);
    }
}