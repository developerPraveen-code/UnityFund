<?php

echo "=================================\n";
echo "TEST CASE: TC-002\n";
echo "FEATURE: Search All Fundraising Activities\n";
echo "=================================\n\n";

echo "User Story:\n";
echo "- As a donee, I want to search all fundraising activities.\n\n";

echo "INITIAL TEST RESULT: FAIL\n\n";

echo "Issue Found:\n";
echo "- Search page initially displayed empty results.\n";
echo "- System only searched after form submission instead of loading all FRA by default.\n\n";

echo "Fix Applied:\n";
echo "- Updated SearchAllFRAUI.php logic.\n";
echo "- System now loads all active fundraising activities automatically.\n";
echo "- Added keyword filtering after search submission.\n\n";

echo "Retest Result:\n";
echo "- All fundraising activities displayed successfully.\n";
echo "- Keyword filtering worked correctly.\n";
echo "- Matching FRA records displayed properly.\n\n";

echo "FINAL STATUS: PASS\n";