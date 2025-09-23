<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'it_asset_management';

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$mode   = isset($_GET['mode']) ? $conn->real_escape_string($_GET['mode']) : 'inventory';

if ($mode === 'unassigned') {
    $sql = "SELECT a.name, a.asset_tag, 'Unassigned' AS assigned_to, a.last_updated
            FROM assets a
            WHERE a.assigned_to IS NULL";
}
 elseif ($mode === 'assigned') {
    // Fetch assigned assets with employee name and assignment date
    $sql = "SELECT CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                   a.name AS asset_name,
                   a.asset_tag,
                   ah.assigned_date
            FROM assets a
            JOIN asset_assignments ah ON a.id = ah.asset_id
            JOIN employees e ON ah.employee_id = e.id
            WHERE ah.unassigned_date IS NULL";
} else {
    // Default: fetch full inventory with employee names (if assigned)
    $sql = "SELECT a.name, a.asset_tag, 
                   IFNULL(CONCAT(e.first_name, ' ', e.last_name), 'Unassigned') AS assigned_to, 
                   a.last_updated
            FROM assets a
            LEFT JOIN employees e ON a.assigned_to = e.id";

    if (!empty($search)) {
        $search = "%$search%";
        $sql .= " WHERE a.name LIKE '$search'
                      OR a.asset_tag LIKE '$search'
                      OR CONCAT(e.first_name, ' ', e.last_name) LIKE '$search'";
    }
}

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $output = [];
    while ($row = $result->fetch_assoc()) {
        $output[] = $row;
    }
    echo json_encode($output);
} else {
    echo json_encode(['error' => 'No records found.']);
}

$conn->close();
?>
