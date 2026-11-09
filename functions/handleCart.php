<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . "/../config/connect.php";
require_once __DIR__ . "/csrf.php";
header("Content-Type: text/plain; charset=UTF-8");

function cartResponse($status, $body) {
    http_response_code($status);
    echo $body;
    exit();
}

function cartPostInteger($key, $maximum = PHP_INT_MAX) {
    $value = filter_var($_POST[$key] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => $maximum]]);
    return $value === false ? null : $value;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    cartResponse(405, "405");
}
if (!verifyCsrfToken()) {
    cartResponse(403, "403");
}

if (!isset($_SESSION["auth"], $_SESSION["authUser"]["userId"])) {
    cartResponse(401, "401");
}

if (!isset($_POST["scope"]) || !is_string($_POST["scope"])) {
    cartResponse(400, "400");
}

$userId = (int) $_SESSION["authUser"]["userId"];
$scope = $_POST["scope"];

if ($scope === "add" || $scope === "update") {
    $productId = cartPostInteger("productId");
    $productQuantity = cartPostInteger("productQuantity", 10);
    if ($productId === null || $productQuantity === null) {
        cartResponse(400, "400");
    }

    $productStatement = $conn->prepare("SELECT quantity FROM products WHERE id = ? AND status = 0 LIMIT 1");
    if (!$productStatement) {
        cartResponse(500, "500");
    }
    $productStatement->bind_param("i", $productId);
    $productStatement->execute();
    $productStatement->bind_result($availableQuantity);
    if (!$productStatement->fetch()) {
        $productStatement->close();
        cartResponse(404, "404");
    }
    $productStatement->close();

    if ($availableQuantity < 1 || $productQuantity > min(10, (int) $availableQuantity)) {
        cartResponse(422, "422");
    }

    if ($scope === "add") {
        $existingStatement = $conn->prepare("SELECT id FROM carts WHERE productId = ? AND userId = ? LIMIT 1");
        if (!$existingStatement) {
            cartResponse(500, "500");
        }
        $existingStatement->bind_param("ii", $productId, $userId);
        $existingStatement->execute();
        $existingStatement->store_result();
        $exists = $existingStatement->num_rows > 0;
        $existingStatement->close();

        if ($exists) {
            cartResponse(200, "existing");
        }

        $insertStatement = $conn->prepare("INSERT INTO carts (userId, productId, productQuantity) VALUES (?, ?, ?)");
        if (!$insertStatement) {
            cartResponse(500, "500");
        }
        $insertStatement->bind_param("iii", $userId, $productId, $productQuantity);
        $inserted = $insertStatement->execute();
        $insertStatement->close();
        cartResponse($inserted ? 201 : 500, $inserted ? "201" : "500");
    }

    $updateStatement = $conn->prepare("UPDATE carts SET productQuantity = ? WHERE productId = ? AND userId = ?");
    if (!$updateStatement) {
        cartResponse(500, "500");
    }
    $updateStatement->bind_param("iii", $productQuantity, $productId, $userId);
    $updated = $updateStatement->execute();
    $updateStatement->close();
    cartResponse($updated ? 200 : 500, $updated ? "200" : "500");
}

if ($scope === "delete") {
    $cartId = cartPostInteger("cartId");
    if ($cartId === null) {
        cartResponse(400, "400");
    }

    $deleteStatement = $conn->prepare("DELETE FROM carts WHERE id = ? AND userId = ?");
    if (!$deleteStatement) {
        cartResponse(500, "500");
    }
    $deleteStatement->bind_param("ii", $cartId, $userId);
    $deleted = $deleteStatement->execute() && $deleteStatement->affected_rows > 0;
    $deleteStatement->close();
    cartResponse($deleted ? 200 : 404, $deleted ? "200" : "404");
}

cartResponse(400, "400");
?>
