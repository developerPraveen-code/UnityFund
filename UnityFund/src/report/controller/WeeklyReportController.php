<?php

// USER STORY #45: Generate Weekly Report
// BCE Role: Controller

require_once __DIR__ . '/../entity/WeeklyReport.php';

class WeeklyReportController
{
    private WeeklyReport $weeklyReport;

    public function __construct()
    {
        $this->weeklyReport = new WeeklyReport();
    }

    public function generateWeeklyReport(string $startDate, string $endDate): array
    {
        return $this->weeklyReport->generateWeeklyReport($startDate, $endDate);
    }
}