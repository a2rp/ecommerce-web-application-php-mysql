<?php 
if(!isset($_SESSION)) {
    session_start(); 
}
include ("../config/connect.php");

if (isset($_SESSION["auth"])) {
    if(isset($_POST["placeOrderButton"])) {
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
        $pincode = mysqli_real_escape_string($conn, $_POST["pincode"]);
        $address = mysqli_real_escape_string($conn, $_POST["address"]);
        $paymentMode = mysqli_real_escape_string($conn, $_POST["paymentMode"]);
        $paymentId = mysqli_real_escape_string($conn, $_POST["paymentId"]);

        if ($name == "" || $email == "" || $phone == "" || $pincode == "" || $address == "") {
            $_SESSION["message"] = "All Fields are mandatory";
            header("Location: ../checkout.php");
            exit(0);
        }

        $userId = $_SESSION["authUser"]["userId"];
        $cartsQuery = "SELECT carts.id AS cartId, carts.productId, carts.productQuantity, products.id AS productId, products.name, products.image, products.selling_price FROM carts, products WHERE carts.productId=products.id AND carts.userId='$userId' ORDER BY carts.id DESC";
        $resultCartItems = mysqli_query($conn, $cartsQuery);
        $totalPrice = 0;
        foreach ($resultCartItems as $item) {
            $totalPrice += $item["selling_price"] * $item["productQuantity"];
        }
        // echo "Total Price: ".$totalPrice;

        $trackingNumber = "a2rp-".rand(1111,9999).substr($phone,2);
        $userId = $_SESSION["authUser"]["userId"];
        $insertQuery = "INSERT INTO orders 
        (trackingNumber, userId, name, email, phone, address, pincode, totalPrice, paymentMode, paymentId) VALUES 
        ('$trackingNumber', '$userId', '$name', '$email', '$phone', '$address', '$pincode', '$totalPrice', '$paymentMode', '$paymentId')";
        $resultInsert = mysqli_query($conn, $insertQuery);
        if ($resultInsert) {
            $orderId = mysqli_insert_id($conn);
            foreach ($resultCartItems as $cartItem) {
                $productId = $cartItem["productId"];
                $productQuantity = $cartItem["productQuantity"];
                $price = $cartItem["selling_price"];

                $insertItemsQuery = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$orderId', '$productId', '$productQuantity', '$price')";
                $resultInsertItems = mysqli_query($conn, $insertItemsQuery);

                $productQuery = "SELECT * FROM products WHERE id = '$productId' LIMIT 1";
                $resultProductQuery = mysqli_query($conn, $productQuery);

                $productData = mysqli_fetch_array($resultProductQuery);
                $currentQuantity = $productData["quantity"];
                $newQuantity = $currentQuantity - $productQuantity;

                $updateQuery = "UPDATE products SET quantity='$newQuantity' WHERE id = '$productId'";
                $resultUpdateQuery = mysqli_query($conn, $updateQuery);
            }

            $deleteCartQuery = "DELETE FROM carts WHERE userId = '$userId'";
            $deleteCartQueryResult = mysqli_query($conn, $deleteCartQuery);

            $_SESSION["message"]  = "Order placed successfully.";
            header("Location: ../myOrders.php");
            die();
        }
    }
} else {
    header("Location: ../index.php");
}
?>
