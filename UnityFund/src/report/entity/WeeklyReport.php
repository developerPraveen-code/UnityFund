<?php

// USER STORY #45: Generate Weekly Report
// BCE Role: Entity
// Entity only reads report data from database. No $_SESSION is used here.

require_once __DIR__ . '/../../shared/database/Database.php';

class WeeklyReport
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function generateWeeklyReport(string $startDate, string $endDate): array
    {
        $donationSql = "SELECT 
                            COALESCE(SUM(amount), 0) AS totalFundsRaised,
                            COUNT(*) AS totalDonations,
                            COUNT(*) AS totalTransactions
                        FROM donations
                        WHERE donation_date BETWEEN :startDate AND :endDate";

        $donationStmt = $this->conn->prepare($donationSql);
        $donationStmt->bindParam(':startDate', $startDate);
        $donationStmt->bindParam(':endDate', $endDate);
        $donationStmt->execute();

        $donationData = $donationStmt->fetch(PDO::FETCH_ASSOC);

        $fraSql = "SELECT COUNT(*) AS completedFRA
                   FROM fundraising_activities
                   WHERE status = 'Completed'
                   AND end_date BETWEEN :startDate AND :endDate";

        $fraStmt = $this->conn->prepare($fraSql);
        $fraStmt->bindParam(':startDate', $startDate);
        $fraStmt->bindParam(':endDate', $endDate);
        $fraStmt->execute();

        $fraData = $fraStmt->fetch(PDO::FETCH_ASSOC);

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalFundsRaised' => (float) $donationData['totalFundsRaised'],
            'totalDonations' => (int) $donationData['totalDonations'],
            'totalTransactions' => (int) $donationData['totalTransactions'],
            'completedFRA' => (int) $fraData['completedFRA']
        ];
    }
}