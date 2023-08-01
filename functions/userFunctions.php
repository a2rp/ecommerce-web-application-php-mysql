<?php
if(!isset($_SESSION)) { session_start();  }
include "config/connect.php";

function getAllActive($table) {
    global $conn;
    $query = "SELECT * from $table WHERE status = 0";
    return $queryRun = mysqli_query($conn, $query);
}

function getAllTrending() {
    global $conn;
    $query = "SELECT * from products WHERE trending = 1";
    return $queryRun = mysqli_query($conn, $query);
}

function getIdActive($table, $id) {
    global $conn;
    $query = "SELECT * from $table where id='$id' AND status=0";
    return $queryRun = mysqli_query($conn, $query);
}

function getSlugActive($table, $slug) {
    global $conn;
    $query = "SELECT * from $table where slug='$slug' AND status=0 LIMIT 1";
    return $queryRun = mysqli_query($conn, $query);
}

function getProductsByCategoryId($categoryId) {
    global $conn;
    $query = "SELECT * from products where category_id='$categoryId' AND status=0";
    return $queryRun = mysqli_query($conn, $query);
}

function getCartItems() {
    global $conn;
    $userId = $_SESSION["authUser"]["userId"];
    $cartsQuery = "SELECT carts.id AS cartId, carts.productId, carts.productQuantity, products.id AS productId, products.name, products.image, products.selling_price FROM carts, products WHERE carts.productId=products.id AND carts.userId='$userId' ORDER BY carts.id DESC";
    return $result = mysqli_query($conn, $cartsQuery);
}

function getOrders() {
    global $conn;
    if (!isset($_SESSION["authUser"]["userId"])) {
        redirect("Please Login", "../../index.php");
        exit();
    }
    $userId = $_SESSION["authUser"]["userId"];
    $ordersQuery = "SELECT * FROM orders WHERE userId='$userId' ORDER BY id DESC";
    return $result = mysqli_query($conn, $ordersQuery);
}

function checkValidTrackingNumber ($trackingNumber) {
    global $conn;
    $userId = $_SESSION["authUser"]["userId"];
    $query = "SELECT * FROM orders WHERE trackingNumber='$trackingNumber' and userId='$userId'";
    return $result = mysqli_query($conn, $query);
}

function redirect($message, $url) {
    $_SESSION["message"] = $message;
    header("Location: ".$url);
    exit();
}

?>