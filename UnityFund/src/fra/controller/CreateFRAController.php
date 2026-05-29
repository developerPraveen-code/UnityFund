<?php

require_once __DIR__ . '/../entity/FundraisingActivity.php';

class CreateFRAController
{
    private FundraisingActivity $fundraisingActivity;

    public function __construct()
    {
        $this->fundraisingActivity = new FundraisingActivity();
    }

    public function createFRA(
        int $fundraiserId,
        string $title,
        string $description,
        float $goalAmount,
        string $category
    ): string {
        return $this->fundraisingActivity->createFRA(
            $fundraiserId,
            $title,
            $description,
            $goalAmount,
            $category
        );
    }
}