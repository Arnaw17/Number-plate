<?php
session_start(); // Start the session

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

if (isset($_GET['registration_number'])) {
    $registration_number = $_GET['registration_number'];

    $query = "SELECT * FROM challans WHERE registration_number = '$registration_number'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'No challan found for this vehicle.']);
    }
}
?>
