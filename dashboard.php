<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'myevent');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set pagination variables
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Search filter
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Query to fetch product data with optional search
$sql = "SELECT * FROM products 
        WHERE name LIKE '%$search%' 
        LIMIT $items_per_page OFFSET $offset";

$result = $conn->query($sql);

// Total number of products for pagination
$total_sql = "SELECT COUNT(*) AS total FROM products WHERE name LIKE '%$search%'";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];

// Prepare JSON response
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$response = [
    'products' => $data,
    'total_pages' => ceil($total_items / $items_per_page),
    'current_page' => $page
];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
