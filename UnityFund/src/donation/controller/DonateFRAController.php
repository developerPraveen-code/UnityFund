<?php

require_once __DIR__ . '/../../fra/entity/FundraisingActivity.php';
require_once __DIR__ . '/../entity/Donation.php';

class DonateFRAController
{
    private FundraisingActivity $fraEntity;
    private Donation $donationEntity;

    public function __construct()
    {
        $this->fraEntity = new FundraisingActivity();
        $this->donationEntity = new Donation();
    }

    public function getActiveFRA(): array
    {
        return $this->fraEntity->getAllFRA();
    }

    public function getFRA(int $fraId): ?array
    {
        return $this->fraEntity->getFRAById($fraId);
    }

    public function makeDonation(int $userId, int $fraId, float $amount): string
    {
        if ($amount <= 0) {
            return 'Donation amount must be greater than zero.';
        }

        $fra = $this->fraEntity->getFRAById($fraId);

        if (!$fra) {
            return 'Fundraising activity not found.';
        }

        if ($fra['status'] !== 'Active') {
            return 'Donations can only be made to active campaigns.';
        }

        $success = $this->donationEntity->createDonation(
            $userId,
            $fra['title'],
            $fra['category'],
            $amount
        );

        if ($success) {
            $this->fraEntity->addToCurrentAmount($fraId, $amount);
            return 'Donation of $' . number_format($amount, 2) . ' to "' . $fra['title'] . '" was successful!';
        }

        return 'Donation failed. Please try again.';
    }
}
