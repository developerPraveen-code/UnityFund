<?php

// USER STORY #32: Search Completed FRA History
// BCE Role: Controller
// Searches completed fundraising activities for a fundraiser.

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class SearchHistoryController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function searchCompletedFRA(int $fundraiserId, string $keyword): array
    {
        return $this->fundraisingActivity->searchCompletedFRA($fundraiserId, $keyword);
    }
}