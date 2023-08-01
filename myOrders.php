<?php 
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "includes/header.php";
    include "functions/userFunctions.php";
?>

<style><?php include "userAssets/myOrders.css"; ?></style>
<script><?php include "userAssets/js/custom.js"; ?></script>

<div class="myOrdersContainer">
    <div class="breadCrumbs">
        <h1 class="breadcrumbs">
            <a href="<?php echo "index.php"; ?>">Home</a>
            / 
            <a href="<?php echo "myOrders.php"; ?>">My Orders</a>
        </h1>

        <?php if (isset($_SESSION["message"])) { ?>
            <div class="alertMessage"><?php echo $_SESSION["message"]; ?></div>
            <script><?php include("../assets/jQuery v3.7.0.js"); ?></script>
            <script>$(".alertMessage").show().delay(5000).fadeOut();</script>
        <?php } unset($_SESSION["message"]); ?>

        <div class="myOrdersContentContainer">
            <?php
                $orders = getOrders();
                if (mysqli_num_rows($orders)>0) {
                    ?>
                    <table class="myOrdersTable">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Tracking Number</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($orders as $order) {
                                // print_r ($order);
                                // echo "<br />";
                                ?>
                                <tr>
                                    <td><?php echo $order["id"]?></td>
                                    <td><?php echo $order["trackingNumber"]?></td>
                                    <td>&#8377; <?php echo $order["totalPrice"]?></td>
                                    <td><?php echo $order["createdAt"]?></td>
                                    <td><a href="viewOrder.php?trackingNumber=<?php echo $order["trackingNumber"]; ?>">View Details</a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    ?>
                        <h1 style="text-align: center; font-size: 20px;">No Orders Yet.</h1>
                    <?php
                }
            ?>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
