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
    if (mysqli_num_rows($orderData)<1) {
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
                <input type="text" value="<?php if (isset($data)) {echo escapeHtml($data["name"]);} ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="email">Email</label>
                    <input type="text" value="<?php echo escapeHtml($data["email"]); ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="phone">Phone</label>
                    <input type="text" value="<?php echo escapeHtml($data["phone"]); ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="trackingNumber">Tracking Number</label>
                    <input type="text" value="<?php echo escapeHtml($data["trackingNumber"]); ?>" disabled />
                </div>
                <div class="detailGroup">
                    <label for="address">Address</label>
                    <textarea rows="5" disabled><?php echo escapeHtml($data["address"]); ?></textarea>
                </div>
                <div class="detailGroup">
                    <label for="pincode">Pin code</label>
                    <input type="text" value="<?php echo escapeHtml($data["pincode"]); ?>" disabled />
                </div>
            </div>
            <div class="orderDetailsContainer">
                <h1 class="heading">Order Details</h1>
                <table class="orderDetailsTable">
                    <thead><tr><th>Product</th><th>Price</th><th>Quantity</th></tr></thead>
                    <tbody>
                <?php
                    $orderQuery = "SELECT products.name, products.image, order_items.price AS orderedPrice, order_items.quantity FROM order_items INNER JOIN products ON products.id = order_items.product_id WHERE order_items.order_id = ?";
                    $orderStatement = $conn->prepare($orderQuery);
                    $orderStatement->bind_param("i", $data["id"]);
                    $orderStatement->execute();
                    $resultOrder = $orderStatement->get_result();
                    if (mysqli_num_rows($resultOrder)>0) {
                        foreach ($resultOrder as $singleOrder) {
                            ?>
                            <tr>
                                <td class="imageProductName">
                                    <img src="../uploads/<?php echo escapeHtml($singleOrder["image"]); ?>" alt="<?php echo escapeHtml($singleOrder["name"]); ?>">
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
                <h1 class="totalPrice">Total Price: <span>&#8377; <?php echo $data["totalPrice"]; ?></span></h1>
                <h1 class="paymentMode">Payment mode: <span><?php echo escapeHtml($data["paymentMode"]); ?></span></h1>
                <h1 class="paymentStatus">Order Status:
                    <form action="adminCode.php" method="POST" style="float: right;">
                        <?php csrfInput(); ?>
                        <input type="hidden" name="trackingNumber" id="trackingNumber" value="<?php echo escapeHtml($data["trackingNumber"]); ?>" />
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
