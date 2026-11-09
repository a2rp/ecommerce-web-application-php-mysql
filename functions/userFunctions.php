<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . "/../config/connect.php";
require_once __DIR__ . "/escape.php";

function getAllActive($table) {
    global $conn;
    $allowedTables = ["products", "categories"];
    if (!in_array($table, $allowedTables, true)) {
        return false;
    }

    return mysqli_query($conn, "SELECT * FROM `$table` WHERE status = 0");
}

function getAllTrending() {
    global $conn;
    return mysqli_query($conn, "SELECT * FROM products WHERE trending = 1 AND status = 0");
}

function getIdActive($table, $id) {
    global $conn;
    $allowedTables = ["products", "categories"];
    if (!in_array($table, $allowedTables, true) || filter_var($id, FILTER_VALIDATE_INT) === false) {
        return false;
    }

    $statement = $conn->prepare("SELECT * FROM `$table` WHERE id = ? AND status = 0 LIMIT 1");
    $statement->bind_param("i", $id);
    $statement->execute();
    return $statement->get_result();
}

function getSlugActive($table, $slug) {
    global $conn;
    $allowedTables = ["products", "categories"];
    if (!in_array($table, $allowedTables, true) || !is_string($slug)) {
        return false;
    }

    $statement = $conn->prepare("SELECT * FROM `$table` WHERE slug = ? AND status = 0 LIMIT 1");
    $statement->bind_param("s", $slug);
    $statement->execute();
    return $statement->get_result();
}

function getProductsByCategoryId($categoryId) {
    global $conn;
    if (filter_var($categoryId, FILTER_VALIDATE_INT) === false) {
        return false;
    }

    $statement = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND status = 0");
    $statement->bind_param("i", $categoryId);
    $statement->execute();
    return $statement->get_result();
}

function getCartItems() {
    global $conn;
    if (!isset($_SESSION["authUser"]["userId"])) {
        return false;
    }

    $userId = (int) $_SESSION["authUser"]["userId"];
    $statement = $conn->prepare("SELECT carts.id AS cartId, carts.productId, carts.productQuantity, products.id AS productId, products.name, products.image, products.selling_price FROM carts INNER JOIN products ON carts.productId = products.id WHERE carts.userId = ? ORDER BY carts.id DESC");
    $statement->bind_param("i", $userId);
    $statement->execute();
    return $statement->get_result();
}

function getOrders() {
    global $conn;
    if (!isset($_SESSION["authUser"]["userId"])) {
        redirect("Please log in to view your orders.", "index.php");
    }

    $userId = (int) $_SESSION["authUser"]["userId"];
    $statement = $conn->prepare("SELECT * FROM orders WHERE userId = ? ORDER BY id DESC");
    $statement->bind_param("i", $userId);
    $statement->execute();
    return $statement->get_result();
}

function checkValidTrackingNumber($trackingNumber) {
    global $conn;
    if (!isset($_SESSION["authUser"]["userId"]) || !is_string($trackingNumber)) {
        return false;
    }

    $userId = (int) $_SESSION["authUser"]["userId"];
    $statement = $conn->prepare("SELECT * FROM orders WHERE trackingNumber = ? AND userId = ? LIMIT 1");
    $statement->bind_param("si", $trackingNumber, $userId);
    $statement->execute();
    return $statement->get_result();
}

function redirect($message, $url) {
    $_SESSION["message"] = $message;
    header("Location: " . $url);
    exit();
}
?>
