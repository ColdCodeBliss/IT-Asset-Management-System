<?php
$conn = new mysqli('localhost', 'root', '', 'it_asset_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_POST['employee_id'];
$asset_id = $_POST['asset_id'];
$today = date('Y-m-d');

// 1. Update asset record
$update = $conn->prepare("UPDATE assets SET assigned_to = ? WHERE id = ?");
$update->bind_param("ii", $employee_id, $asset_id);
$update->execute();

// 2. Insert into asset_assignments
$insert = $conn->prepare("INSERT INTO asset_assignments (asset_id, employee_id, assigned_date) VALUES (?, ?, ?)");
$insert->bind_param("iis", $asset_id, $employee_id, $today);
$insert->execute();

$conn->close();

header("Location: index.php");
exit();
?>