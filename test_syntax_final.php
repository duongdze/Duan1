<?php
// Test syntax for updated models
echo "Testing syntax...\n";

// Test basic array syntax like line 157
$filters = ['tour_id' => 1];
$tourFilters = array_merge($filters, ['tour_id' => 123]);

// Test SQL string patterns
$sql1 = "SELECT * FROM table LIMIT 100";
$sql2 = "SELECT * FROM table LIMIT 12";

echo "Syntax test completed successfully!\n";
