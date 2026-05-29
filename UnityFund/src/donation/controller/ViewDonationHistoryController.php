<?php

// USER STORY #35: View Donation History
// BCE Role: Controller
// Retrieves donation history from Donation entity.

require_once __DIR__ . '/../entity/Donation.php';

class ViewDonationHistoryController
{
    private Donation $donation;

    public function __construct()
    {
        $this->donation = new Donation();
    }

    public function getDonationHistory(int $doneeId): array
    {
        return $this->donation->getDonationHistory($doneeId);
    }
}