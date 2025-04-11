<?php
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phoneno = $conn->real_escape_string($_POST['phoneno']);
    $password = $conn->real_escape_string($_POST['password']); // Encrypt password

    // Insert data into the table
    $sql = "INSERT INTO rc (fullname, email, phoneno, password) VALUES ('$fullname', '$email', '$phoneno', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Register Successfully You can now Login');
            window.location.href='index.php';
            </script>";;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>