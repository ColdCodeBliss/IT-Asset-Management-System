<?php
// Start session
session_start();

// Security check: Redirect to login page if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("Access denied.");
}

// Connect to DB
$conn = new mysqli('localhost', 'root', '', 'it_asset_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Export as CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="maintenance_logs.csv"');

    $output = fopen("php://output", "w");
    fputcsv($output, ['Asset Name', 'Asset Tag', 'Date', 'Issue', 'Resolution', 'Cost', 'Performed By']);

    $query = "SELECT a.name, a.asset_tag, m.maintenance_date, m.issue_description, m.resolution, m.cost, m.performed_by
              FROM maintenance_logs m
              JOIN assets a ON m.asset_id = a.id
              ORDER BY m.maintenance_date DESC";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Regular display
echo "<h3 style='color:#4CAF50;'>Maintenance Log Records</h3>";
echo "<a href='maintenance_history.php?export=csv' style='color: #4CAF50; display: inline-block; margin-bottom: 10px;'>Download as CSV</a>";

echo "<table style='width:100%; border-collapse: collapse; color: white;'>";
echo "<tr style='background-color:#333; color:#4CAF50;'>
        <th style='padding: 8px;'>Asset Name</th>
        <th style='padding: 8px;'>Asset Tag</th>
        <th style='padding: 8px;'>Date</th>
        <th style='padding: 8px;'>Issue</th>
        <th style='padding: 8px;'>Resolution</th>
        <th style='padding: 8px;'>Cost</th>
        <th style='padding: 8px;'>Performed By</th>
      </tr>";

$query = "SELECT a.name, a.asset_tag, m.maintenance_date, m.issue_description, m.resolution, m.cost, m.performed_by
          FROM maintenance_logs m
          JOIN assets a ON m.asset_id = a.id
          ORDER BY m.maintenance_date DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr style='background-color:#1e1e1e;'>
                <td style='padding: 8px;'>{$row['name']}</td>
                <td style='padding: 8px;'>{$row['asset_tag']}</td>
                <td style='padding: 8px;'>{$row['maintenance_date']}</td>
                <td style='padding: 8px;'>{$row['issue_description']}</td>
                <td style='padding: 8px;'>{$row['resolution']}</td>
                <td style='padding: 8px;'>{$row['cost']}</td>
                <td style='padding: 8px;'>{$row['performed_by']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7' style='padding: 10px; text-align:center;'>No maintenance records found.</td></tr>";
}

echo "</table>";

$conn->close();
?>
