<?php

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class DisableFRAController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function disableFRA(int $fraId): ?array
    {
        return $this->fundraisingActivity->disableFRA($fraId);
    }
}