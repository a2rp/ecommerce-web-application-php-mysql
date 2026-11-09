<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . "/../config/connect.php";
require_once __DIR__ . "/csrf.php";

function checkoutRedirect($message, $url = "../checkout.php") {
    $_SESSION["message"] = $message;
    header("Location: " . $url);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["placeOrderButton"])) {
    http_response_code(405);
    exit("Method not allowed.");
}
if (!verifyCsrfToken()) {
    http_response_code(403);
    exit("Invalid request token.");
}

if (!isset($_SESSION["auth"], $_SESSION["authUser"]["userId"])) {
    checkoutRedirect("Please log in before placing an order.", "../login.php");
}

$name = trim((string) ($_POST["name"] ?? ""));
$email = trim((string) ($_POST["email"] ?? ""));
$phone = trim((string) ($_POST["phone"] ?? ""));
$pincode = trim((string) ($_POST["pincode"] ?? ""));
$address = trim((string) ($_POST["address"] ?? ""));

$validPhone = preg_match("/^[0-9+() .-]{7,24}$/", $phone) === 1;
$validPincode = preg_match("/^[A-Za-z0-9 -]{3,16}$/", $pincode) === 1;
if (
    $name === "" || strlen($name) > 120 ||
    filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($email) > 254 ||
    !$validPhone || !$validPincode || $address === "" || strlen($address) > 1000
) {
    checkoutRedirect("Enter valid billing and delivery details.");
}

$userId = (int) $_SESSION["authUser"]["userId"];

try {
    if (!$conn->begin_transaction()) {
        throw new RuntimeException("Could not begin checkout transaction.");
    }

    $cartStatement = $conn->prepare("SELECT carts.productId, carts.productQuantity, products.name, products.image, products.selling_price, products.quantity AS availableQuantity, products.status AS productStatus FROM carts INNER JOIN products ON products.id = carts.productId WHERE carts.userId = ? ORDER BY carts.id DESC FOR UPDATE");
    if (!$cartStatement) {
        throw new RuntimeException("Could not prepare cart lookup.");
    }
    $cartStatement->bind_param("i", $userId);
    if (!$cartStatement->execute()) {
        throw new RuntimeException("Could not load cart.");
    }
    $cartResult = $cartStatement->get_result();
    $cartItems = $cartResult ? $cartResult->fetch_all(MYSQLI_ASSOC) : [];
    $cartStatement->close();

    if (!$cartItems) {
        $conn->rollback();
        checkoutRedirect("Your cart is empty or contains unavailable products.");
    }

    $totalCents = 0;
    foreach ($cartItems as $item) {
        $quantity = (int) $item["productQuantity"];
        $available = (int) $item["availableQuantity"];
        if ((int) $item["productStatus"] !== 0 || $quantity < 1 || $quantity > $available) {
            $conn->rollback();
            checkoutRedirect("A product in your cart is unavailable or no longer has enough stock.", "../cart.php");
        }
        $unitPriceCents = (int) round((float) $item["selling_price"] * 100);
        $totalCents += $unitPriceCents * $quantity;
    }

    $totalPrice = number_format($totalCents / 100, 2, ".", "");
    $trackingNumber = "a2rp-" . bin2hex(random_bytes(8));
    $paymentMode = "COD";
    $paymentId = "";

    $orderStatement = $conn->prepare("INSERT INTO orders (trackingNumber, userId, name, email, phone, address, pincode, totalPrice, paymentMode, paymentId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$orderStatement) {
        throw new RuntimeException("Could not prepare order creation.");
    }
    $orderStatement->bind_param("sissssssss", $trackingNumber, $userId, $name, $email, $phone, $address, $pincode, $totalPrice, $paymentMode, $paymentId);
    if (!$orderStatement->execute()) {
        throw new RuntimeException("Could not create order.");
    }
    $orderId = $conn->insert_id;
    $orderStatement->close();

    $itemStatement = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stockStatement = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ? AND quantity >= ? AND status = 0");
    if (!$itemStatement || !$stockStatement) {
        throw new RuntimeException("Could not prepare order item updates.");
    }

    foreach ($cartItems as $item) {
        $productId = (int) $item["productId"];
        $quantity = (int) $item["productQuantity"];
        $price = (string) $item["selling_price"];

        $itemStatement->bind_param("iiis", $orderId, $productId, $quantity, $price);
        if (!$itemStatement->execute()) {
            throw new RuntimeException("Could not save order items.");
        }

        $stockStatement->bind_param("iii", $quantity, $productId, $quantity);
        if (!$stockStatement->execute() || $stockStatement->affected_rows !== 1) {
            throw new RuntimeException("Product stock changed during checkout.");
        }
    }

    $itemStatement->close();
    $stockStatement->close();

    $deleteCartStatement = $conn->prepare("DELETE FROM carts WHERE userId = ?");
    if (!$deleteCartStatement) {
        throw new RuntimeException("Could not prepare cart cleanup.");
    }
    $deleteCartStatement->bind_param("i", $userId);
    if (!$deleteCartStatement->execute()) {
        throw new RuntimeException("Could not clear cart.");
    }
    $deleteCartStatement->close();

    if (!$conn->commit()) {
        throw new RuntimeException("Could not complete order transaction.");
    }
} catch (Throwable $error) {
    $conn->rollback();
    error_log("Checkout failed: " . $error->getMessage());
    checkoutRedirect("We could not place your order. Please try again.");
}

$_SESSION["message"] = "Order placed successfully.";
header("Location: ../myOrders.php");
exit();
?>
