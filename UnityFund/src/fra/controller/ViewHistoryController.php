<?php

// USER STORY #33: View Completed FRA History
// BCE Role: Controller
// Retrieves all completed fundraising activities for a fundraiser.

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class ViewHistoryController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function getCompletedFRA(int $fundraiserId): array
    {
        return $this->fundraisingActivity->getCompletedFRA($fundraiserId);
    }
}