<?php

// USER STORY #34: Search Donation History
// USER STORY #35: View Donation History
// BCE Role: Entity
// Stores and retrieves donee donation history records.

require_once __DIR__ . '/../../shared/database/Database.php';

class Donation
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // USER STORY #35: View Donation History
    public function getDonationHistory(int $doneeId): array
    {
        $sql = "SELECT
                    donation_id AS \"donationId\",
                    donee_id AS \"doneeId\",
                    fra_title AS \"fraTitle\",
                    category,
                    amount,
                    donation_date AS \"donationDate\",
                    status
                FROM donations
                WHERE donee_id = :doneeId
                ORDER BY donation_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':doneeId', $doneeId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createDonation(int $userId, string $fraTitle, string $category, float $amount): bool
    {
        $sql = "INSERT INTO donations (donee_id, fra_title, category, amount, donation_date, status)
                VALUES (:userId, :fraTitle, :category, :amount, CURRENT_DATE, 'Completed')";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':fraTitle', $fraTitle);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':amount', $amount);
        return $stmt->execute();
    }

    // USER STORY #34: Search Donation History
    public function searchDonationHistory(int $doneeId, string $keyword): array
    {
        $sql = "SELECT
                    donation_id AS \"donationId\",
                    donee_id AS \"doneeId\",
                    fra_title AS \"fraTitle\",
                    category,
                    amount,
                    donation_date AS \"donationDate\",
                    status
                FROM donations
                WHERE donee_id = :doneeId
                AND (
                    fra_title LIKE :keyword
                    OR category LIKE :keyword
                    OR status LIKE :keyword
                    OR donation_date LIKE :keyword
                )
                ORDER BY donation_date DESC";

        $searchKeyword = '%' . $keyword . '%';

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':doneeId', $doneeId, PDO::PARAM_INT);
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}