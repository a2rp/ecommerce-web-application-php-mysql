<?php 
    if(!isset($_SESSION)) {
        session_start(); 
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
            <div class="alertMessage"><?php echo $_SESSION["message"]; ?></div>
            <script><?php include("../assets/jQuery v3.7.0.js"); ?></script>
            <script>$(".alertMessage").show().delay(5000).fadeOut();</script>
        <?php } unset($_SESSION["message"]); ?>

        <div class="deliveryOrderDetailsContainer">
            <div class="deliveryDetailsContainer">
                <h1 class="heading">Delivery Details</h1>
                <div class="detailGroup">
                    <label for="name">Name</label>
                    <input type="text" value="<?php echo $order["name"]; ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="email">Email</label>
                    <input type="text" value="<?php echo $order["email"]; ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="phone">Phone</label>
                    <input type="text" value="<?php echo $order["phone"]; ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="trackingNumber">Tracking Number</label>
                    <input type="text" value="<?php echo $order["trackingNumber"]; ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="address">Address</label>
                    <textarea rows="5" disabled><?php echo $order["address"]; ?></textarea>
                </div>
                <div class="detailGroup">
                    <label for="pincode">Pin code</label>
                    <input type="text" value="<?php echo $order["pincode"]; ?>" disabled />
                </div>
            </div>
            <div class="orderDetailsContainer">
                <h1 class="heading">Order Details</h1>
                <table class="orderDetailsTable">
                    <thead><tr><th>Product</th><th>Price</th><th>Quantity</th></tr></thead>
                    <tbody>
                <?php
                    $userId = $_SESSION["authUser"]["userId"];
                    $orderQuery = "SELECT orders.id as orderId, orders.trackingNumber, orders.userId, products.*, order_items.* FROM orders, order_items, products WHERE orders.userId='$userId' AND order_items.order_id=orders.id AND products.id=order_items.product_id AND orders.trackingNumber='$trackingNumber'";
                    $resultOrder = mysqli_query($conn, $orderQuery);
                    if (mysqli_num_rows($resultOrder)>0) {
                        foreach ($resultOrder as $singleOrder) {
                            ?>
                            <tr>
                                <td class="imageProductName">
                                    <img src="uploads/<?php echo $singleOrder["image"]; ?>" alt="<?php echo $singleOrder["name"]; ?>">
                                    <?php echo $singleOrder["name"]; ?>
                                </td>
                                <td>&#8377; <?php echo $singleOrder["selling_price"]; ?></td>
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
                <h1 class="paymentMode">Payment mode: <span><?php echo $order["paymentMode"]; ?></span></h1>
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
