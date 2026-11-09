<?php
require_once __DIR__ . "/../config/connect.php";
require_once __DIR__ . "/escape.php";

function getAll($table) {
    global $conn;
    $allowedTables = ["products", "categories"];
    if (!in_array($table, $allowedTables, true)) {
        return false;
    }

    return mysqli_query($conn, "SELECT * FROM `$table`");
}

function getAllOrders() {
    global $conn;
    return mysqli_query($conn, "SELECT * FROM orders WHERE status = 0");
}

function getById($table, $id) {
    global $conn;
    $allowedTables = ["products", "categories"];
    if (!in_array($table, $allowedTables, true) || filter_var($id, FILTER_VALIDATE_INT) === false) {
        return false;
    }

    $statement = $conn->prepare("SELECT * FROM `$table` WHERE id = ? LIMIT 1");
    $statement->bind_param("i", $id);
    $statement->execute();
    return $statement->get_result();
}

function checkValidTrackingNumber($trackingNumber) {
    global $conn;
    if (!is_string($trackingNumber)) {
        return false;
    }

    $statement = $conn->prepare("SELECT * FROM orders WHERE trackingNumber = ? LIMIT 1");
    $statement->bind_param("s", $trackingNumber);
    $statement->execute();
    return $statement->get_result();
}

function redirect($message, $url) {
    $_SESSION["message"] = $message;
    header("Location: " . $url);
    exit();
}
?>
