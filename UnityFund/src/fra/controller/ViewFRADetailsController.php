<?php

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class ViewFRADetailsController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function getFRA(int $fraId): ?array
    {
        return $this->fundraisingActivity->getFRA($fraId);
    }
}