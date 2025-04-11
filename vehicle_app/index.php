<?php 
    require("Connection.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vehicle Registration Search</title>
        <script src="https://kit.fontawesome.com/e083b1a7aa.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <header class="head">
            <div class="header-container">
                <h4>Vehicle Search</h4>
                <button class="login-btn" onclick="openPopup('loginPopup')">Login</button> 
                <button class="login-btn" onclick="openPopup('registerPopup')">Register</button>
                <!-- Admin Login Button -->
                <button class="login-btn admin-btn" onclick="openPopup('adminPopup')">Admin Login</button>
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
                    <form class="rc_vehicleForm" onsubmit="return checkLoginStatus()">
                        <div class="inputwrapper">
                            <input name="vehicleNumber" class="input_inputBox" type="text" placeholder="JH 01 CL 4486" id="input" maxlength="100" minlength="0" autocomplete="off" value="">
                        </div>
                        <img class="rc_searchImg" src="image/searchweb.00139779.svg" alt="search" loading="lazy">
                    </form>
                </div>
            </div>
        </section>

        <section class="whatrc">
            <div class="whatrc1">
                <h1 style="text-align: center; line-height:20px; margin-bottom:6px;">What is RC?</h1>
                <p style="text-align: center;">Registration Certificate (RC) is a crucial document that serves as official proof of vehicle ownership and registration. <br>
                    The RC provides comprehensive details about the vehicle, offering important information 
                    that is essential for legal<br> and administrative purposes.</p>
                <img src="image/Rc.svg">
            </div>
        </section>

        <div class="overlay" id="overlay"></div>

        <div class="popup" id="loginPopup"> 
            <span class="popup-close" onclick="closePopup('loginPopup')">&times;</span> 
            <div class="popup-header">Login</div> 
            <form method="POST" action="login.php"> 
                <input name="identifier" type="text" placeholder="Email/Phone Number" required> 
                <input name="password" type="password" placeholder="Password" required> 
                <button type="submit">Login</button> 
            </form>
        </div>

        <div class="popup" id="registerPopup"> 
            <span class="popup-close" onclick="closePopup('registerPopup')">&times;</span> 
            <div class="popup-header">Register</div> 
            <form method="POST" action="register.php" onsubmit="return validateForm()"> 
                <input name="fullname" type="text" placeholder="Full Name" required> 
                <input name="email" type="email" placeholder="Email" required> 
                <input name="phoneno" type="phoneno" placeholder="Phone number" required> 
                <input name="password" type="password" placeholder="Password" required> 
                <button type="submit">Register</button> 
            </form>
        </div> 
<!-- Admin Login Popup -->
<div class="popup" id="adminPopup"> 
            <span class="popup-close" onclick="closePopup('adminPopup')">&times;</span> 
            <div class="popup-header">Admin Login</div> 
            <form method="POST" action="admin-authenticate.php" onsubmit="return validateAdminForm()"> 
                <input name="username" type="text" placeholder="Admin Username" required> 
                <input name="password" type="password" placeholder="Password" required> 
                <button type="submit">Login</button> 
            </form>
        </div>
        <footer>
            <div class="footer-container">
                <p>&copy; 2024 CarInfo | All Rights Reserved</p>
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

            function isLoggedIn() {
                // Return false to simulate a "not logged in" state
                return Boolean(sessionStorage.getItem("isLoggedIn"));
            }

            function checkLoginStatus() {
                if (!isLoggedIn()) {
                    alert("Please log in first to search for vehicle details."); // Alert message
                    // Optionally redirect to login page
                    window.location.href = "login.php";
                    return false; // Prevent form submission
                }
                return true; // Allow form submission if logged in
            }
            // function validateAdminForm() {
            //     const username = document.querySelector('input[name="username"]').value;
            //     const password = document.querySelector('input[name="password"]').value;

            //     if (username.trim() === "" || password.trim() === "") {
            //         // alert("Both fields are required.");
            //         return false;
            //     }
            //     return true;
            // }
        </script>
    </body>
</html>
