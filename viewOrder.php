<?php
    if(!isset($_SESSION)) {
                    session_start();
    }
    if (!isset($_SESSION["auth"], $_SESSION["authUser"]["userId"])) {
        $_SESSION["message"] = "Please log in to view your order.";
        header("Location: login.php");
        exit();
    }
    include "includes/header.php";
    include "functions/userFunctions.php";
?>

<style><?php include "userAssets/viewOrder.css"; ?></style>
<script><?php include "userAssets/js/custom.js"; ?></script>

<?php
if (isset($_GET["trackingNumber"])) {
    $trackingNumber = $_GET["trackingNumber"];
    $resultTrackingNumber = checkValidTrackingNumber($trackingNumber);
    if (mysqli_num_rows($resultTrackingNumber)<1) {
        ?>
        <h1 style="padding: 15px;">Something went wrong</h1>
        <?php
        die();
    }
} else {
    ?>
    <h1 style="padding: 15px;">Something went wrong</h1>
    <?php
        die();
    }
    $order = mysqli_fetch_array($resultTrackingNumber);
?>

<div class="orderContainer">
    <div class="breadCrumbs">
        <h1 class="breadcrumbs">
            <a href="<?php echo "index.php"; ?>">Home</a>
            /
            <a href="<?php echo "myOrders.php"; ?>">My Orders</a>
            /
            <a href="#">View Order</a>

            <a href="myOrders.php" class="goBack">Go Back</a>
        </h1>

        <?php if (isset($_SESSION["message"])) { ?>
            <div class="alertMessage"><?php echo escapeHtml($_SESSION["message"]); ?></div>
            <script><?php include("../assets/jQuery v3.7.0.js"); ?></script>
            <script>$(".alertMessage").show().delay(5000).fadeOut();</script>
        <?php } unset($_SESSION["message"]); ?>

        <div class="deliveryOrderDetailsContainer">
            <div class="deliveryDetailsContainer">
                <h1 class="heading">Delivery Details</h1>
                <div class="detailGroup">
                    <label for="name">Name</label>
                    <input type="text" value="<?php echo escapeHtml($order["name"]); ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="email">Email</label>
                    <input type="text" value="<?php echo escapeHtml($order["email"]); ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="phone">Phone</label>
                    <input type="text" value="<?php echo escapeHtml($order["phone"]); ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="trackingNumber">Tracking Number</label>
                    <input type="text" value="<?php echo escapeHtml($order["trackingNumber"]); ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="address">Address</label>
                    <textarea rows="5" disabled><?php echo escapeHtml($order["address"]); ?></textarea>
                </div>
                <div class="detailGroup">
                    <label for="pincode">Pin code</label>
                    <input type="text" value="<?php echo escapeHtml($order["pincode"]); ?>" disabled />
                </div>
            </div>
            <div class="orderDetailsContainer">
                <h1 class="heading">Order Details</h1>
                <table class="orderDetailsTable">
                    <thead><tr><th>Product</th><th>Price</th><th>Quantity</th></tr></thead>
                    <tbody>
                <?php
                    $userId = $_SESSION["authUser"]["userId"];
                    $orderQuery = "SELECT products.name, products.image, order_items.price AS orderedPrice, order_items.quantity FROM orders INNER JOIN order_items ON order_items.order_id = orders.id INNER JOIN products ON products.id = order_items.product_id WHERE orders.userId = ? AND orders.trackingNumber = ?";
                    $orderStatement = $conn->prepare($orderQuery);
                    $orderStatement->bind_param("is", $userId, $trackingNumber);
                    $orderStatement->execute();
                    $resultOrder = $orderStatement->get_result();
                    if (mysqli_num_rows($resultOrder)>0) {
                        foreach ($resultOrder as $singleOrder) {
                            ?>
                            <tr>
                                <td class="imageProductName">
                                    <img src="uploads/<?php echo escapeHtml($singleOrder["image"]); ?>" alt="<?php echo escapeHtml($singleOrder["name"]); ?>">
                                    <?php echo escapeHtml($singleOrder["name"]); ?>
                                </td>
                                <td>&#8377; <?php echo $singleOrder["orderedPrice"]; ?></td>
                                <td>x * <?php echo $singleOrder["quantity"]; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "No data Found";
                    }
                ?>
                    </tbody>
                </table>
                <h1 class="totalPrice">Total Price: <span>&#8377; <?php echo $order["totalPrice"]; ?></span></h1>
                <h1 class="paymentMode">Payment mode: <span><?php echo escapeHtml($order["paymentMode"]); ?></span></h1>
                <h1 class="paymentStatus">Payment Status: <span><?php
                    if ($order["status"]==0) {
                        echo "Under Process";
                    } else if ($order["status"]==1) {
                        echo "Completed";
                    } else if ($order["status"]==2) {
                        echo "Cancelled";
                    }
                ?></span></h1>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
