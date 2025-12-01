<?php
// Simple syntax test
echo "Testing syntax...";

// Test array syntax like line 52
$whereConditions = ["B.booking_date BETWEEN :date_from AND :date_to"];
$params = [':date_from' => '2023-01-01', ':date_to' => '2023-12-31'];

// Test array merge like line 154
$filters = ['tour_id' => 1];
$tourFilters = array_merge($filters, ['tour_id' => 123]);

echo "Syntax test passed!";
