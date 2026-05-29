<?php

// USER STORY #34: Search Donation History
// BCE Role: Controller
// Handles searching donee donation history.

require_once __DIR__ . '/../entity/Donation.php';

class SearchDonationHistoryController
{
    private Donation $donation;

    public function __construct()
    {
        $this->donation = new Donation();
    }

    public function searchDonationHistory(int $doneeId, string $keyword): array
    {
        return $this->donation->searchDonationHistory($doneeId, $keyword);
    }
}