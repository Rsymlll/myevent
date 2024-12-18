<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'myevent');

// Pagination variables
$itemsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Calculate the offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Build query with search filter
$query = "SELECT id, name, picture, description, type, price, quantity 
          FROM products 
          WHERE name LIKE ? OR description LIKE ? 
          LIMIT $offset, $itemsPerPage";
$stmt = $conn->prepare($query);
$searchTerm = '%' . $search . '%';
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// Get total pages
$totalQuery = "SELECT COUNT(*) FROM products WHERE name LIKE ? OR description LIKE ?";
$stmtTotal = $conn->prepare($totalQuery);
$stmtTotal->bind_param("ss", $searchTerm, $searchTerm);
$stmtTotal->execute();
$totalResult = $stmtTotal->get_result()->fetch_row();
$totalItems = $totalResult[0];
$totalPages = ceil($totalItems / $itemsPerPage);

// Return data as JSON for AJAX
echo json_encode([
    'products' => $products,
    'currentPage' => $page,
    'totalPages' => $totalPages
]);

$conn->close();
?>
