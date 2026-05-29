<?php

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class ViewFRAController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function getFRAList(int $fundraiserId): array
    {
        return $this->fundraisingActivity->getFRAList($fundraiserId);
    }
}