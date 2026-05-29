<?php

// USER STORY #46: Generate Monthly Report
// BCE Role: Entity

require_once __DIR__ . '/../../shared/database/Database.php';

class MonthlyReport
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function generateReport(int $month, int $year): array
    {
        $donationSql = "SELECT 
                            COUNT(*) AS totalDonations,
                            COALESCE(SUM(amount), 0) AS totalFundsRaised,
                            COALESCE(AVG(amount), 0) AS averageDonation
                        FROM donations
                        WHERE MONTH(donation_date) = :month
                        AND YEAR(donation_date) = :year";

        $donationStmt = $this->conn->prepare($donationSql);
        $donationStmt->bindParam(':month', $month, PDO::PARAM_INT);
        $donationStmt->bindParam(':year', $year, PDO::PARAM_INT);
        $donationStmt->execute();

        $donationData = $donationStmt->fetch(PDO::FETCH_ASSOC);

        $fraSql = "SELECT COUNT(*) AS completedFRA
                   FROM fundraising_activities
                   WHERE status = 'Completed'
                   AND MONTH(end_date) = :month
                   AND YEAR(end_date) = :year";

        $fraStmt = $this->conn->prepare($fraSql);
        $fraStmt->bindParam(':month', $month, PDO::PARAM_INT);
        $fraStmt->bindParam(':year', $year, PDO::PARAM_INT);
        $fraStmt->execute();

        $fraData = $fraStmt->fetch(PDO::FETCH_ASSOC);

        return [
            'month' => date('F', mktime(0, 0, 0, $month, 1)),
            'year' => $year,
            'totalFundsRaised' => (float)$donationData['totalFundsRaised'],
            'totalDonations' => (int)$donationData['totalDonations'],
            'completedFRA' => (int)$fraData['completedFRA'],
            'averageDonation' => round((float)$donationData['averageDonation'], 2)
        ];
    }
}