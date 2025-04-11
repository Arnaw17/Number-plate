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
    $challan_details = $_POST['challan_details'];
    $amount_due = $_POST['amount_due'];
    $status = $_POST['status'];

    $query = "INSERT INTO challans (registration_number, challan_details, amount_due, status) 
              VALUES ('$registration_number', '$challan_details', '$amount_due', '$status')";

    if ($conn->query($query)) {
        echo "Challan added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    header("Location: admin-dashboard.php");
}
?>
