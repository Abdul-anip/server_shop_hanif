<?php
include_once 'dbconnect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['search']) || empty(trim($_GET['search']))) {
    echo json_encode([]);
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = $conn->real_escape_string($search);
$stmt = $conn->prepare("SELECT * FROM product_items WHERE name LIKE ? OR category LIKE ?");
$searchTerm = "%$search%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$itemproduct = array();
while($row = $result->fetch_assoc()){
    $itemproduct[] = $row;
}

echo json_encode($itemproduct);
$stmt->close();
$conn->close();
?>