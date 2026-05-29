<?php

// USER STORY #46: Generate Monthly Report
// BCE Role: Controller

require_once __DIR__ . '/../entity/MonthlyReport.php';

class MonthlyReportController
{
    private MonthlyReport $monthlyReport;

    public function __construct()
    {
        $this->monthlyReport = new MonthlyReport();
    }

    public function generateReport(int $month, int $year): array
    {
        return $this->monthlyReport->generateReport($month, $year);
    }
}