<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rc_details";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicleNumber = $conn->real_escape_string($_POST['vehicleNumber']); // Sanitize user input

    // Validate vehicle number format (e.g., state-district-letters-numbers)
    if (!preg_match('/^[A-Za-z0-9]+$/', $vehicleNumber)) {
        echo "Invalid vehicle number format. Please enter a valid number.";
    } else {
        // Perform the search query
        $sql = "SELECT * FROM vehicles WHERE registration_number = '$vehicleNumber'";
        $result = $conn->query($sql);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Search</title>
    <link rel="stylesheet" href="vehiclesearch.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Vehicle Search</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="admin-dashboard.php">Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="search-bar">
            <form method="POST" action="vehiclesearchnumber.php">
                <input type="text" name="vehicleNumber" id="searchInput" placeholder="Search by Registration Number">
                <button type="submit">Search</button>
            </form>
        </div>
        <table border="1" cellpadding="10" cellspacing="0" id="vehicleTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Registration Number</th>
                    <th>Owner Name</th>
                    <th>Car Model</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["registration_number"] . "</td>";
                        echo "<td>" . $row["owner_name"] . "</td>";
                        echo "<td>" . $row["car_model"] . "</td>";
                        echo "</tr>";
                    }
                } elseif (isset($result)) {
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>