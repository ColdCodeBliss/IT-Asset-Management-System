<?php
// Start session
session_start();

// Security check: Redirect to login page if the user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Asset Management</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1f1f1f, #121212);
            color: #ffffff;
        }
        header {
            background-color: rgba(31, 31, 31, 0.95);
            color: #ffffff;
            padding: 15px 20px;
            display: flex;
            flex-direction: column; /* changed from row to column */
            align-items: center; /* center everything horizontally */
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(8px);
        }
        header h2 {
            margin: 0 0 10px 0;
            color: #4CAF50;
            text-align: center;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
            scrollbar-width: thin;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            flex-wrap: nowrap;
        }
        header button {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            flex-shrink: 0; /* Prevents button from shrinking in scroll */
        }
        header button:hover {
            background-color: #4CAF50;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        header button img {
            max-width: 20px;
            max-height: 20px;
            vertical-align: middle;
        }
        #search-bar {
            display: block;
            margin: 20px auto;
            padding: 12px;
            width: 50%;
            border-radius: 5px;
            border: 1px solid #333;
            background-color: #1e1e1e;
            color: #fff;
            transition: box-shadow 0.3s;
        }
        #search-bar:focus {
            box-shadow: 0 0 8px #4CAF50;
            outline: none;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #1e1e1e;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        th {
            background-color: #333;
            color: #4CAF50;
            cursor: pointer;
        }
        tr:hover {
            background-color: #444;
        }
        #assign-form-container,
        #maintenance-form-container,
        #maintenance-history-container {
            display: none;
            padding: 20px;
            width: 90%;
            margin: 20px auto;
            background-color: rgba(30,30,30,0.95);
            border-radius: 8px;
            border: 1px solid #444;
            backdrop-filter: blur(6px);
        }
        h3 {
            color: #4CAF50;
        }
        select,
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #555;
            background-color: #333;
            color: white;
            width: 100%;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        form button[type="submit"] {
            width: auto;
            align-self: center;
            padding: 10px 20px;
            background-color: #006400;
        }
        form button[type="submit"]:hover {
            background-color: #4CAF50;
        }
        button:hover {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>
    <header>
        <h2>IT Asset Management</h2>
        <div class="button-group">
            <button onclick="goToInventory()"> <img src="icons/inventory.png" alt="Inventory"> Inventory</button>
            <button onclick="filterUnassigned()"> <img src="icons/unassigned.png" alt="Unassigned"> Unassigned</button>
            <button onclick="filterAssigned()"> <img src="icons/assigned.png" alt="Assigned"> Assigned To</button>
            <button onclick="toggleAssignForm()"> <img src="icons/assign.png" alt="Assign"> Assign Asset</button>
            <button onclick="toggleMaintenanceForm()"> <img src="icons/maintenance2.png" alt="Maintenance"> Assign Maintenance</button>
            <button onclick="toggleMaintenanceHistory()"> <img src="icons/history2.png" alt="History"> Maintenance History</button>
            <button onclick="toggleAssignmentHistory()"> <img src="icons/history2.png" alt="Assignment History"> Assignment History</button>
            <button onclick="toggleAddAssetForm()"> <img src="icons/plus.png" alt="Add Asset"> Add Asset</button>
            <button onclick="toggleAddEmployeeForm()"> <img src="icons/plus.png" alt="Add Employee"> Add Employee</button>
            <button onclick="signOut()"> <img src="icons/logout.png" alt="Sign Out"> Sign Out </button>
        </div>
    </header>


    <input type="text" id="search-bar" placeholder="Search by asset name, tag, assigned user...">

    <table>
        <thead id="table-headers">
            <tr>
                <th>Name</th>
                <th>Asset Tag</th>
                <th onclick="sortByAssignedTo()">Assigned To</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody id="asset-table"></tbody>
    </table>

    <div id="assign-form-container">
        <h3>Assign Asset to Employee</h3>
        <form action="assign_asset.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            <label for="employee_id">Select Employee:</label>
            <select name="employee_id" required>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'it_asset_management');
                $res = $conn->query("SELECT id, first_name, last_name FROM employees");
                while ($row = $res->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['first_name']} {$row['last_name']}</option>";
                }
                ?>
            </select>

            <label for="asset_id">Select Unassigned Asset:</label>
            <select name="asset_id" required>
                <?php
                $res2 = $conn->query("SELECT id, name, asset_tag FROM assets WHERE assigned_to IS NULL");
                while ($row = $res2->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']} ({$row['asset_tag']})</option>";
                }
                ?>
            </select>

            <button id="assignBtn" type="submit">Assign</button>

        </form>
    </div>

    <div id="maintenance-form-container">
        <h3>Submit Maintenance Log</h3>
        <form action="submit_maintenance.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
            <label for="asset_id">Select Asset:</label>
            <select name="asset_id" required>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'it_asset_management');
                $res3 = $conn->query("SELECT id, name, asset_tag FROM assets");
                while ($row = $res3->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']} ({$row['asset_tag']})</option>";
                }
                ?>
            </select>

            <input type="date" name="maintenance_date" required>
            <textarea name="issue_description" placeholder="Describe the issue..." required></textarea>
            <textarea name="resolution" placeholder="Resolution (if any)..."></textarea>
            <input type="number" name="cost" step="0.01" placeholder="Cost">
            <input type="text" name="performed_by" placeholder="Performed by" required>

            <button id="logBtn" type="submit">Submit Log</button>

        </form>
    </div>

    <div id="maintenance-history-container">
        <h3>Maintenance History</h3>
        <iframe src="maintenance_history.php" style="width:100%; height:400px; background:#1e1e1e; border: none;"></iframe>
    </div>

    <div id="assignment-history-container" style="display:none; padding: 20px; width: 90%; margin: 20px auto; background-color: rgba(30,30,30,0.95); border-radius: 8px; border: 1px solid #444;">
        <h3>Assignment History</h3>
        <iframe src="assignment_history.php" style="width:100%; height:400px; background:#1e1e1e; border: none;"></iframe>
    </div>

    <div id="add-asset-form-container" style="display:none; padding: 20px; width: 90%; margin: 20px auto; background-color: rgba(30,30,30,0.95); border-radius: 8px; border: 1px solid #444;">
    <h3>Add New Asset</h3>
    <form action="add_asset.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
        <input type="text" name="asset_tag" placeholder="Asset Tag (MC=Microphone, MN=Monitor, KB=Keyboard, RT=Router, MS=Mouse) ..." required>
        <input type="text" name="name" placeholder="Asset Name" required>
        <input type="text" name="category" placeholder="Category">
        <textarea name="description" placeholder="Description"></textarea>
        <input type="date" name="purchase_date">
        <input type="number" name="original_cost" step="0.01" placeholder="Original Cost">
        <input type="number" name="current_value" step="0.01" placeholder="Current Value">
        <select name="status">
            <option value="Available">Available</option>
            <option value="In Use">In Use</option>
            <option value="In Repair">In Repair</option>
            <option value="Decommissioned">Decommissioned</option>
        </select>
        <button type="submit">Add Asset</button>
    </form>
</div>

<div id="add-employee-form-container" style="display:none; padding: 20px; width: 90%; margin: 20px auto; background-color: rgba(30,30,30,0.95); border-radius: 8px; border: 1px solid #444;">
    <h3>Add New Employee</h3>
    <form action="add_employee.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="department" placeholder="Department">
        <input type="email" name="email" placeholder="Email">
        <button type="submit">Add Employee</button>
    </form>
</div>


    <script>
        const searchBar = document.getElementById('search-bar');
        const assetTable = document.getElementById('asset-table');
        const tableHeaders = document.getElementById('table-headers');
        let currentMode = 'inventory';
        let currentData = [];
        let sortDirection = 1;

        async function fetchData(search = '', mode = 'inventory') {
            currentMode = mode;
            const response = await fetch(`backend.php?search=${encodeURIComponent(search)}&mode=${encodeURIComponent(mode)}`);
            const data = await response.json();
            currentData = data;
            renderTable(data, mode);
        }

        function renderTable(data, mode = 'inventory') {
            assetTable.innerHTML = '';

            if (data.error || data.length === 0) {
                assetTable.innerHTML = '<tr><td colspan="4">No records found.</td></tr>';
                return;
            }

            if (mode === 'assigned') {
                tableHeaders.innerHTML = `
                    <tr>
                        <th>Employee Name</th>
                        <th>Asset Name</th>
                        <th>Asset Tag</th>
                        <th>Assigned Date</th>
                    </tr>
                `;
                data.forEach(entry => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${entry.employee_name}</td>
                        <td>${entry.asset_name}</td>
                        <td>${entry.asset_tag}</td>
                        <td>${entry.assigned_date}</td>
                    `;
                    assetTable.appendChild(row);
                });
            } else {
                tableHeaders.innerHTML = `
                    <tr>
                        <th>Name</th>
                        <th>Asset Tag</th>
                        <th onclick="sortByAssignedTo()">Assigned To</th>
                        <th>Last Updated</th>
                    </tr>
                `;
                data.forEach(entry => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${entry.name}</td>
                        <td>${entry.asset_tag}</td>
                        <td>${entry.assigned_to}</td>
                        <td>${entry.last_updated}</td>
                    `;
                    assetTable.appendChild(row);
                });
            }
        }

        function sortByAssignedTo() {
            currentData.sort((a, b) => {
                const nameA = a.assigned_to?.toLowerCase() || '';
                const nameB = b.assigned_to?.toLowerCase() || '';
                return nameA.localeCompare(nameB) * sortDirection;
            });
            sortDirection *= -1;
            renderTable(currentData, currentMode);
        }

        function goToInventory() {
            fetchData('', 'inventory');
            document.getElementById('search-bar').style.display = 'block';
        }

        function filterUnassigned() {
            fetchData('', 'unassigned');
            const searchBar = document.getElementById('search-bar');
            searchBar.value = ''; // Clear any existing search text
            searchBar.style.display = 'none'; // Hide the search bar
        }

        function filterAssigned() {
            fetchData('', 'assigned');
            const searchBar = document.getElementById('search-bar');
            searchBar.value = ''; // Clear any existing search text
            searchBar.style.display = 'none'; // Hide the search bar
        }

        function toggleAssignForm() {
            const form = document.getElementById('assign-form-container');
            const isVisible = form.style.display === 'block';
            form.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
}

        function toggleMaintenanceForm() {
            const form = document.getElementById('maintenance-form-container');
            const isVisible = form.style.display === 'block';
            form.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
}

        function toggleMaintenanceHistory() {
            const form = document.getElementById('maintenance-history-container');
            const isVisible = form.style.display === 'block';
            form.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
}

        function toggleAssignmentHistory() {
            const form = document.getElementById('assignment-history-container');
            const isVisible = form.style.display === 'block';
            form.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
}


        function toggleAddAssetForm() {
    const form = document.getElementById('add-asset-form-container');
    const isVisible = form.style.display === 'block';
    form.style.display = isVisible ? 'none' : 'block';
    if (!isVisible) {
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function toggleAddEmployeeForm() {
    const form = document.getElementById('add-employee-form-container');
    const isVisible = form.style.display === 'block';
    form.style.display = isVisible ? 'none' : 'block';
    if (!isVisible) {
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}


        function signOut() {
            window.location.href = 'logout.php';
        }

        searchBar.addEventListener('input', () => {
            const query = searchBar.value.trim();
            fetchData(query, currentMode);
        });

        fetchData();

        function playChime() {
            const sound = document.getElementById('chime-sound');
            if (sound) {
                sound.currentTime = 0;
                sound.play();
            }
}


    </script>

    <audio id="chimeSound" src="sounds/chime.mp3" preload="auto"></audio>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const chimeSound = document.getElementById("chimeSound");

    const assignForm = document.querySelector("#assign-form-container form");
    const logForm = document.querySelector("#maintenance-form-container form");

    if (assignForm) {
        assignForm.addEventListener("submit", function(e) {
            e.preventDefault();
            chimeSound.currentTime = 0;
            chimeSound.play();
            setTimeout(() => this.submit(), 400); // Adjust if chime is longer
        });
    }

    if (logForm) {
        logForm.addEventListener("submit", function(e) {
            e.preventDefault();
            chimeSound.currentTime = 0;
            chimeSound.play();
            setTimeout(() => this.submit(), 400); // Adjust as needed
        });
    }
});
</script>


</body>
</html>
