<?php

// USER STORY #44: Generate Daily Report
// BCE Role: Entity
// Entity only reads report data from database. No $_SESSION is used here.

require_once __DIR__ . '/../../shared/database/Database.php';

class DailyReport
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function generateDailyReport(string $selectedDate): array
    {
        $donationSql = "SELECT 
                            COALESCE(SUM(amount), 0) AS totalFundsRaised,
                            COUNT(*) AS totalDonations,
                            COUNT(*) AS totalTransactions
                        FROM donations
                        WHERE donation_date = :selectedDate";

        $donationStmt = $this->conn->prepare($donationSql);
        $donationStmt->bindParam(':selectedDate', $selectedDate);
        $donationStmt->execute();

        $donationData = $donationStmt->fetch(PDO::FETCH_ASSOC);

        $fraSql = "SELECT COUNT(*) AS completedFRA
                   FROM fundraising_activities
                   WHERE status = 'Completed'
                   AND end_date = :selectedDate";

        $fraStmt = $this->conn->prepare($fraSql);
        $fraStmt->bindParam(':selectedDate', $selectedDate);
        $fraStmt->execute();

        $fraData = $fraStmt->fetch(PDO::FETCH_ASSOC);

        return [
            'selectedDate' => $selectedDate,
            'totalFundsRaised' => (float) $donationData['totalFundsRaised'],
            'totalDonations' => (int) $donationData['totalDonations'],
            'totalTransactions' => (int) $donationData['totalTransactions'],
            'completedFRA' => (int) $fraData['completedFRA']
        ];
    }
}