<?php
require("Connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-dashboard.css">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <a href="javascript:history.back()" class="back-btn">← Back</a>
    <header class="admin-header">
        <h1>Admin Dashboard</h1>
        <button onclick="openPopup('addVehiclePopup')">Add New Vehicle</button>
        <button onclick="logout()">Logout</button>
    </header>

    <section class="dashboard-content">
        <h2>Vehicle Database</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search by Registration Number">
            <button onclick="searchVehicle()">Search</button>
        </div>
        <table border="1" cellpadding="10" cellspacing="0" id="vehicleTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Registration Number</th>
                    <th>Owner Name</th>
                    <th>Car Model</th>
                    <th>State</th>
                    <th>District</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="vehicleTableBody">
                <?php
                session_start();

                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "rc_details";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $query = "SELECT * FROM vehicles";
                $result = $conn->query($query);

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['registration_number']}</td>
                            <td>{$row['owner_name']}</td>
                            <td>{$row['car_model']}</td>
                            <td>{$row['state']}</td>
                            <td>{$row['district']}</td>
                            <td>{$row['details']}</td>
                            <td>
                                <button onclick=\"checkChallan('{$row['registration_number']}')\">Check Challan</button>
                                <button onclick=\"openAddChallanPopup('{$row['registration_number']}')\">Add Challan</button>
                                <button onclick=\"deleteRecord({$row['id']})\">Delete</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Add Vehicle Popup -->
    <div class="popup" id="addVehiclePopup">
        <span class="popup-close" onclick="closePopup('addVehiclePopup')">&times;</span>
        <h3>Add New Vehicle</h3>
        <form method="POST" action="process-add.php">
            <input type="text" name="registration_number" placeholder="Registration Number" required>
            <input type="text" name="owner_name" placeholder="Owner Name" required>
            <input type="text" name="car_model" placeholder="Car Model" required>
            <input type="text" name="state" placeholder="State" required>
            <input type="text" name="district" placeholder="District" required>
            <textarea name="details" placeholder="Additional Details"></textarea>
            <button type="submit">Add Vehicle</button>
        </form>
    </div>

    <!-- Add Challan Popup -->
    <div class="popup" id="addChallanPopup">
        <span class="popup-close" onclick="closePopup('addChallanPopup')">&times;</span>
        <h3>Add Challan</h3>
        <form method="POST" action="process-add-challan.php">
            <input type="hidden" name="registration_number" id="challanRegistrationNumber">
            <textarea name="challan_details" placeholder="Challan Details" required></textarea>
            <input type="number" step="0.01" name="amount_due" placeholder="Amount Due" required>
            <select name="status" required>
                <option value="Pending">Pending</option>
                <option value="Paid">Paid</option>
            </select>
            <button type="submit">Add Challan</button>
        </form>
    </div>

    <!-- Challan Details Popup -->
    <div class="popup" id="challanPopup">
        <span class="popup-close" onclick="closePopup('challanPopup')">&times;</span>
        <h3>Challan Details</h3>
        <div id="challanDetails"></div>
    </div>

    <script>
        function openPopup(popupId) {
            document.getElementById(popupId).style.display = 'block';
        }

        function closePopup(popupId) {
            document.getElementById(popupId).style.display = 'none';
        }

        function searchVehicle() {
            const input = document.getElementById('searchInput').value.trim();
            const rows = document.querySelectorAll('#vehicleTableBody tr');

            rows.forEach(row => {
                const regNumber = row.cells[1].textContent;
                if (regNumber.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function checkChallan(registrationNumber) {
            fetch(`get-challan.php?registration_number=${registrationNumber}`)
                .then(response => response.json())
                .then(data => {
                    const challanPopup = document.getElementById('challanPopup');
                    const detailsDiv = document.getElementById('challanDetails');

                    if (data.error) {
                        detailsDiv.innerHTML = `<p>${data.error}</p>`;
                    } else {
                        detailsDiv.innerHTML = `
                            <p>Registration Number: ${data.registration_number}</p>
                            <p>Challan Details: ${data.challan_details}</p>
                            <p>Amount Due: ${data.amount_due}</p>
                            <p>Status: ${data.status}</p>
                        `;
                    }

                    openPopup('challanPopup');
                })
                .catch(error => {
                    console.error('Error fetching challan details:', error);
                });
        }

        function openAddChallanPopup(registrationNumber) {
            document.getElementById('challanRegistrationNumber').value = registrationNumber;
            openPopup('addChallanPopup');
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch(`process-delete.php?id=${id}`, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Record deleted successfully.');
                            location.reload();
                        } else {
                            alert(`Error: ${data.message}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting record:', error);
                        alert('An error occurred while trying to delete the record.');
                    });
            }
        }

        function logout() {
            alert("Logged out successfully.");
            window.location.href = 'admin-login.php';
        }
    </script>
</body>

</html>