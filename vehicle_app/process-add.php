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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $registration_number = $_POST['registration_number'];
    $owner_name = $_POST['owner_name'];
    $car_model = $_POST['car_model'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $details = $_POST['details'];

    $query = "INSERT INTO vehicles (registration_number, owner_name, car_model, state, district, details) 
              VALUES ('$registration_number', '$owner_name', '$car_model', '$state', '$district', '$details')";

    if ($conn->query($query)) {
        echo "New vehicle added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    header("Location: admin-dashboard.php");
}
?>
