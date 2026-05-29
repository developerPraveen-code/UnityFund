<?php

// USER STORY: View Specific User Profile
// BCE Role: Controller

require_once __DIR__ . '/../entity/UserProfile.php';

class ViewUserProfileController
{
    private UserProfile $userProfile;

    public function __construct()
    {
        $this->userProfile = new UserProfile();
    }

    public function getProfile(int $profileId): ?array
    {
        return $this->userProfile->getProfile($profileId);
    }
}