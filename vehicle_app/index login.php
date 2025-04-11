<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}$username = $_SESSION['username']; // Get the username from the session
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vehicle Registration Search</title>
        <script src="https://kit.fontawesome.com/e083b1a7aa.js" ></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <header class="head">
            <div class="header-container">
                <h4>Vehicle Search</h4>
                <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                <a class="login-btn" href=" index.php">Logout</a>
            </div>
        </header>

        <nav class="breadcrumb">
            <div class="home">
                <h4>Home > RC Search</h4>
            </div>
        </nav>

        <section class="intro">
            <div class="home">
                <h2>Check RC Details & Stay Updated</h2>
            </div>
        </section>

        <section class="car-info-light">
            <div class="home">
                <h4>CarInfo brings you all the important registration details of any vehicle in India.</h4>
            </div>
        </section>

        <section class="imgcar">
            <img src="image/carimage.png" alt="Car Image">
            <div class="rc_rcSearch">
                <p class="rc_enterVehicleNumber">Enter vehicle number to get details</p>
                <div class="rc_rcSearchInput">
                    <img class="rc_india_FqzIL" src="image/indweb.360cc100.svg" alt="ind" loading="lazy">
                    <form method = "POST" action="vehiclesearchnumber.php"  class="rc_vehicleForm">
                        <div class="inputwrapper">
                            <input name="vehicleNumber" class="input_inputBox" type="text" placeholder="JH 01 CL 4486" id="input" maxlength="100" minlength="0" autocomplete="off" value="">
                        </div>
                        
                       <input type="image" name="submit" img class="rc_searchImg" src="image/searchweb.00139779.svg" alt="search" /> 
                    </form>
                </div>
            </div>
        </section>
        <div class="container">
        <h1>Upload Vehicle Image</h1>
        <form action="upload_image.php" method="post" enctype="multipart/form-data">
        <label for="file">Upload Vehicle Image:</label>
        <input type="file" name="file" accept="image/*" required>
        <button type="submit">Upload and Search</button>
    </form>
        <Section class="whatrc">
            <div class="whatrc1">
                <h1 style="text-align: center;  line-height:20px; margin-bottom:6px;">What is RC?</h1>
                <p style="text-align: center;"> Registration Certificate (RC) is a crucial document that serves as official proof of vehicle ownership and registration. <br>
                    The RC provides comprehensive details about the vehicle, offering important information 
                    that is essential for legal<br> and administrative purposes.</p>
                <img src="image/Rc.svg">
                
            </div>
        </Section>
        <div class="overlay" id="overlay"></div>

      

      
        
        <footer>
            <div class="footer-container">
                <p>&copy; 2024  | All Rights Reserved</p>
            </div>
        </footer>
        <script>
            function openPopup(popupId) {
                document.getElementById(popupId).style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            }

            function closePopup(popupId) {
                document.getElementById(popupId).style.display = 'none';
                document.getElementById('overlay').style.display = 'none';
            }

            function validateForm() {
        const fullname = document.querySelector('input[name="fullname"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const phoneno = document.querySelector('input[name="phoneno"]').value;
        const password = document.querySelector('input[name="password"]').value;

        if (fullname.trim() === "" || email.trim() === "" || phoneno.trim() === "" || password.trim() === "") {
            alert("All fields are required.");
            return false;
        }

        if (!/^\d{10}$/.test(phoneno)) {
            alert("Enter a valid 10-digit phone number.");
            return false;
        }

        return true;
    }
    
        </script>
    </body>
</html>
