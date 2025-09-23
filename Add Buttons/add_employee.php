<?php
$conn = new mysqli('localhost', 'root', '', 'it_asset_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$department = $_POST['department'] ?? null;
$email = $_POST['email'] ?? null;

$stmt = $conn->prepare("INSERT INTO employees (first_name, last_name, department, email) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $first_name, $last_name, $department, $email);
$stmt->execute();

$conn->close();

header("Location: index.php");
exit();
?>
