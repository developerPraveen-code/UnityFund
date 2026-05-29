<?php

// USER STORY #5: Search User Profiles
// BCE Role: Controller

require_once __DIR__ . '/../entity/UserProfile.php';

class SearchUserProfileController
{
    private UserProfile $userProfile;

    public function __construct()
    {
        $this->userProfile = new UserProfile();
    }

    public function searchUserProfile(string $searchTerm): array
    {
        return $this->userProfile->searchUserProfile($searchTerm);
    }
}