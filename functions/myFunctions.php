<?php
include "../config/connect.php";

function getAll($table) {
    global $conn;
    $query = "SELECT * from $table";
    return $queryRun = mysqli_query($conn, $query);
}

function getAllOrders() {
    global $conn;
    $query = "SELECT * from orders WHERE status='0'";
    return $queryRun = mysqli_query($conn, $query);
}

function getById($table, $id) {
    global $conn;
    $query = "SELECT * from $table where id=$id";
    return $queryRun = mysqli_query($conn, $query);
}

function checkValidTrackingNumber ($trackingNumber) {
    global $conn;
    $query = "SELECT * FROM orders WHERE trackingNumber='$trackingNumber'";
    return $result = mysqli_query($conn, $query);
}

function redirect($message, $url) {
    $_SESSION["message"] = $message;
    header("Location: ".$url);
    exit();
}
?>