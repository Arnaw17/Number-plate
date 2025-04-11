<?php
session_start(); // Start session

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rc details";

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
    if (!preg_match('/^[A-Za-z]{2}\d{2}[A-Za-z]{2}\d+$/', $vehicleNumber)) {
        echo "Invalid vehicle number format. Please enter a valid number (e.g., JH01CL4486).";
        exit;
    }

    // Query to fetch vehicle details
    $sql = "SELECT * FROM vehicles WHERE registration_number = '$vehicleNumber'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the details of the vehicle
        $vehicle = $result->fetch_assoc();
        echo "<h2>Vehicle Details:</h2>";
        echo "<p><strong>Registration Number:</strong> " . $vehicle['registration_number'] . "</p>";
        echo "<p><strong>Owner Name:</strong> " . $vehicle['owner_name'] . "</p>";
        echo "<p><strong>Car Model:</strong> " . $vehicle['car_model'] . "</p>";
        echo "<p><strong>State:</strong> " . $vehicle['state'] . "</p>";
        echo "<p><strong>District:</strong> " . $vehicle['district'] . "</p>";
        echo "<p><strong>Additional Details:</strong> " . $vehicle['details'] . "</p>";
    } else {
        echo "No details found for the provided vehicle number.";
    }
}

$conn->close();
?>
