<?php

// USER STORY #28: View Shortlist Count
// BCE Role: Controller
// Retrieves how many times a fundraising activity has been saved by donees.

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class ViewShortlistController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function viewShortlistCount(int $fraId): int
    {
        return $this->fundraisingActivity->viewShortlistCount($fraId);
    }
}