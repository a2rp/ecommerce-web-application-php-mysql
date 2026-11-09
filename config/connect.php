<?php
$host = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASSWORD") ?: "";
$database = getenv("DB_NAME") ?: "admin_panel";

$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    http_response_code(500);
    exit("The application database is unavailable.");
}

if (!mysqli_set_charset($conn, "utf8mb4")) {
    error_log("Could not set the database connection charset to utf8mb4.");
    http_response_code(500);
    exit("The application database connection could not be initialized.");
}
