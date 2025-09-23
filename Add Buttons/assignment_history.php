<?php
$mysqli = new mysqli('localhost', 'root', '', 'it_asset_management');

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

// Check if export is requested
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="assignment_history.csv"');
    $output = fopen("php://output", "w");
    fputcsv($output, ['Asset Name', 'Asset Tag', 'Employee Name', 'Assigned Date']);

    $result = $mysqli->query("
        SELECT a.name AS asset_name, a.asset_tag, CONCAT(e.first_name, ' ', e.last_name) AS employee_name, aa.assigned_date
        FROM asset_assignments aa
        JOIN assets a ON aa.asset_id = a.id
        JOIN employees e ON aa.employee_id = e.id
        ORDER BY aa.assigned_date DESC
    ");

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c2c2c;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #444;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #4CAF50;
        }
        tr:hover {
            background-color: #444;
        }
        .export-btn {
            margin-bottom: 10px;
            background-color: #333;
            color: #fff;
            padding: 8px 14px;
            text-decoration: none;
            border: 1px solid #555;
            border-radius: 4px;
        }
        .export-btn:hover {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>

<a href="?export=csv" class="export-btn">Export to CSV</a>

<table>
    <thead>
        <tr>
            <th>Asset Name</th>
            <th>Asset Tag</th>
            <th>Employee Name</th>
            <th>Assigned Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $mysqli->query("
            SELECT a.name AS asset_name, a.asset_tag, CONCAT(e.first_name, ' ', e.last_name) AS employee_name, aa.assigned_date
            FROM asset_assignments aa
            JOIN assets a ON aa.asset_id = a.id
            JOIN employees e ON aa.employee_id = e.id
            ORDER BY aa.assigned_date DESC
        ");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['asset_name']}</td>
                    <td>{$row['asset_tag']}</td>
                    <td>{$row['employee_name']}</td>
                    <td>{$row['assigned_date']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No assignment history found.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
