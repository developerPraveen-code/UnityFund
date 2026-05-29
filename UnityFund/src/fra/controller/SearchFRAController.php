<?php

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class SearchFRAController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function searchMyFRA(int $fundraiserId, string $keyword): array
    {
        return $this->fundraisingActivity->searchMyFRA($fundraiserId, $keyword);
    }
}