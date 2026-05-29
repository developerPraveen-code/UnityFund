<?php

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class EditFRAController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function updateFRA(
        int $fraId,
        string $title,
        string $description,
        float $goalAmount,
        string $status
    ): ?array {
        return $this->fundraisingActivity->updateFRA(
            $fraId,
            $title,
            $description,
            $goalAmount,
            $status
        );
    }
}