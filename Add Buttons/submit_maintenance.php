<?php
$conn = new mysqli('localhost', 'root', '', 'it_asset_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$asset_id = $_POST['asset_id'];
$maintenance_date = $_POST['maintenance_date'];
$issue_description = $_POST['issue_description'];
$resolution = $_POST['resolution'];
$cost = $_POST['cost'];
$performed_by = $_POST['performed_by'];

// Insert into maintenance_logs
$insert = $conn->prepare("INSERT INTO maintenance_logs (asset_id, maintenance_date, issue_description, resolution, cost, performed_by) VALUES (?, ?, ?, ?, ?, ?)");
$insert->bind_param("isssds", $asset_id, $maintenance_date, $issue_description, $resolution, $cost, $performed_by);
$insert->execute();

// Check if the asset is currently assigned
$result = $conn->query("SELECT assigned_to FROM assets WHERE id = $asset_id");
$row = $result->fetch_assoc();

if ($row && !is_null($row['assigned_to'])) {
    $assigned_to = $row['assigned_to'];
    $today = date('Y-m-d');

    // 1. Update asset to unassign
    $conn->query("UPDATE assets SET assigned_to = NULL WHERE id = $asset_id");

    // 2. Update asset_assignments to log unassigned date
    $conn->query("UPDATE asset_assignments SET unassigned_date = '$today' WHERE asset_id = $asset_id AND unassigned_date IS NULL");
}

$conn->close();
header("Location: index.php");
exit();
?>
