<?php
$conn = new mysqli('localhost', 'root', '', 'it_asset_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$asset_tag = $_POST['asset_tag'];
$name = $_POST['name'];
$category = $_POST['category'] ?? null;
$description = $_POST['description'] ?? null;
$purchase_date = $_POST['purchase_date'] ?? null;
$original_cost = $_POST['original_cost'] ?? null;
$current_value = $_POST['current_value'] ?? null;
$status = $_POST['status'] ?? 'Available';

$stmt = $conn->prepare("INSERT INTO assets (asset_tag, name, category, description, purchase_date, original_cost, current_value, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssdss", $asset_tag, $name, $category, $description, $purchase_date, $original_cost, $current_value, $status);
$stmt->execute();

$conn->close();

header("Location: index.php");
exit();
?>
