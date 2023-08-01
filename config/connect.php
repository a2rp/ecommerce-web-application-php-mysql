<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $databse = "admin_panel";

    $conn = mysqli_connect($host, $username, $password, $databse);
    if (!$conn) {
        die("connection failed".mysqli_connect_error());
    } else {
        // echo "Connection established";
    }
?>