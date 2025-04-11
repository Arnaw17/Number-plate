<?php
session_start(); // Start the session

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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $conn->real_escape_string($_POST['identifier']); // Email or Phone number
    $password = $conn->real_escape_string($_POST['password']); // Password

    // Query to find the user by email or phone number and match password
    $sql = "SELECT * FROM rc WHERE (email = '$identifier' OR phoneno = '$identifier') AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Set session variables
        $_SESSION['username'] = $user['fullname'];

        // Redirect to index login.php
        header("Location: index login.php");
        exit;
    } else {
        echo "Invalid email/phone number or password.";
    }

    $conn->close();
}
?>