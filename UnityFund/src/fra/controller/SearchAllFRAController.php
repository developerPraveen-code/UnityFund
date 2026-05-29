<?php

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class SearchAllFRAController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function searchAllFRA(string $keyword): array
    {
        return $this->fundraisingActivity->searchAllFRA($keyword);
    }
}