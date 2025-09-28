<?php
header("Content-Type: application/json");

// Include your database connection
require_once "config/database.php"; // adjust path if needed

// Get the search query
$q = isset($_GET['q']) ? $_GET['q'] : '';

if ($q !== '') {
    // Prepare the SQL query to prevent SQL injection
    $stmt = $db->prepare("SELECT id, name FROM products WHERE name LIKE CONCAT(?, '%') LIMIT 10");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
} else {
    echo json_encode([]);
}
