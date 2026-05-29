<?php

// USER STORY #27: View Number of Views for Posted FRA
// BCE Role: Controller
// Retrieves posted fundraising activities and their view count.

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class ViewPostedViewsController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function getPostedFRAList(int $fundraiserId): array
    {
        return $this->fundraisingActivity->getFRAList($fundraiserId);
    }
}