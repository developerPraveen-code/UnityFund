<?php

// USER STORY #44: Generate Daily Report
// BCE Role: Controller

require_once __DIR__ . '/../entity/DailyReport.php';

class DailyReportController
{
    private DailyReport $dailyReport;

    public function __construct()
    {
        $this->dailyReport = new DailyReport();
    }

    public function generateDailyReport(string $selectedDate): array
    {
        return $this->dailyReport->generateDailyReport($selectedDate);
    }
}