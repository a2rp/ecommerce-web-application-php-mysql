<?php
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "../middleware/adminVerification.php";
?>
<style><?php include("../assets/viewOrder.css"); ?></style>
<?php
if (isset($_GET["trackingNumber"])) {
    $trackingNumber = $_GET["trackingNumber"];
    $orderData = checkValidTrackingNumber($trackingNumber);
    if (mysqli_num_rows($orderData)<0) {
    ?>
    <h1>error1: something went wrong</h1>
    <?php
    die();
    }
} else {
    ?>
    <h1>error2: something went wrong 2</h1>
    <?php
    die();
}
$data = mysqli_fetch_array($orderData);
?>
<div class="adminContainer">
    <div class="header">
        <?php include "adminHeader.php"?>
    </div>
    <div class="contentSection">
        <div class="controlSection">
            <?php include "adminSidebar.php"; ?>
        </div>
        <div class="mainContent">

        <div class="deliveryOrderDetailsContainer">
            <div class="deliveryDetailsContainer">
                <h1 class="heading">Delivery Details</h1>
                <div class="detailGroup">
                    <label for="name">Name</label>
                    <input type="text" value="<?php if (isset($data)) {echo $data["name"];} ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="email">Email</label>
                    <input type="text" value="<?php echo $data["email"]; ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="phone">Phone</label>
                    <input type="text" value="<?php echo $data["phone"]; ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="trackingNumber">Tracking Number</label>
                    <input type="text" value="<?php echo $data["trackingNumber"]; ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="address">Address</label>
                    <textarea rows="5" disabled><?php echo $data["address"]; ?></textarea>
                </div>
                <div class="detailGroup">
                    <label for="pincode">Pin code</label>
                    <input type="text" value="<?php echo $data["pincode"]; ?>" disabled />
                </div>
            </div>
            <div class="orderDetailsContainer">
                <h1 class="heading">Order Details</h1>
                <table class="orderDetailsTable">
                    <thead><tr><th>Product</th><th>Price</th><th>Quantity</th></tr></thead>
                    <tbody>
                <?php
                    $userId = $_SESSION["authUser"]["userId"];
                    $orderQuery = "SELECT orders.id as orderId, orders.trackingNumber, orders.userId, products.*, order_items.* FROM orders, order_items, products WHERE  order_items.order_id=orders.id AND products.id=order_items.product_id AND orders.trackingNumber='$trackingNumber'";
                    $resultOrder = mysqli_query($conn, $orderQuery);
                    if (mysqli_num_rows($resultOrder)>0) {
                        foreach ($resultOrder as $singleOrder) {
                            ?>
                            <tr>
                                <td class="imageProductName">
                                    <img src="../uploads/<?php echo $singleOrder["image"]; ?>" alt="<?php echo $singleOrder["name"]; ?>">
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
                <h1 class="totalPrice">Total Price: <span>&#8377; <?php echo $data["totalPrice"]; ?></span></h1>
                <h1 class="paymentMode">Payment mode: <span><?php echo $data["paymentMode"]; ?></span></h1>
                <h1 class="paymentStatus">Payment Status:
                    <form action="adminCode.php" method="POST" style="float: right;">
                        <input type="hidden" name="trackingNumber" id="trackingNumber" value="<?php echo $data["trackingNumber"]; ?>" />
                        <select name="orderStatus" id="">
                            <option value="0" <?php echo $data["status"]==0 ? "selected" : "" ?>>Under Process</option>
                            <option value="1" <?php echo $data["status"]==1 ? "selected" : "" ?>>Completed</option>
                            <option value="2" <?php echo $data["status"]==2 ? "selected" : "" ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="updateStatusButton">Update Status</button>
                    </form>
                </h1>
            </div>
        </div>
    </div>
</div>
