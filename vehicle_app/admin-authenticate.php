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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to verify admin credentials
    $query = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Successful login
        session_start();
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_username"] = $username;
        header("Location: admin-dashboard.php");
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password'); window.location.href='admin-login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
