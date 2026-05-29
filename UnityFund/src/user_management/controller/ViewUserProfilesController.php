<?php

// USER STORY: View User Profiles
// BCE Role: Controller

require_once __DIR__ . '/../entity/UserProfile.php';

class ViewUserProfilesController
{
    private UserProfile $userProfile;

    public function __construct()
    {
        $this->userProfile = new UserProfile();
    }

    public function getAllProfiles(): array
    {
        return $this->userProfile->getAllProfiles();
    }
}