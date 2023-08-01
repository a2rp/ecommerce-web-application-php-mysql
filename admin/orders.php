<?php
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "../middleware/adminVerification.php";
?>

<style><?php include("../assets/orders.css"); ?></style>
<div class="adminContainer">
    <div class="header">
        <?php include "adminHeader.php"?>
    </div>
    <div class="contentSection">
        <div class="controlSection">
            <?php include "adminSidebar.php"; ?>
        </div>
        <div class="mainContent">
            <div class="ordersContainer">
            <?php
                $orders = getAllOrders();
                if (mysqli_num_rows($orders)>0) {
                    ?>
                    <table class="myOrdersTable">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>User name</th>
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
                                    <td><?php echo $order["name"]?></td>
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
</div>
