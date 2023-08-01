<?php
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "../middleware/adminVerification.php";
?>

<script><?php include("../assets/jQuery v3.7.0.js"); ?></script>
<style><?php include("../assets/product.css"); ?></style>
<?php include("../assets/js/custom.js"); ?>
<div class="adminContainer">
    <div class="header">
        <?php include "adminHeader.php"?>
    </div>

    <?php if (isset($_SESSION["message"])) { ?>
    <div class="alertMessage"><?php echo $_SESSION["message"]; ?></div>
    <script><?php include("../assets/jQuery v3.7.0.js"); ?></script>
    <script>$(".alertMessage").show().delay(5000).fadeOut();</script>
    <?php } unset($_SESSION["message"]); ?>

    <div class="contentSection">
        <div class="controlSection">
            <?php include "adminSidebar.php"; ?>
        </div>
        <div class="mainContent">
            <h2 class="heading">
                <div class="allProductsInfo">All Products</div>
                <a class="newProductLink" href="../admin/addProduct.php">ADD NEW PRODUCT</a>
            </h2>
            <table class="productTable">
                <thead>
                    <tr>
                        <th>Id</th><th>Name</th><th>Image</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <?php
                    $allProducts = getAll("products");
                    if (mysqli_num_rows($allProducts)>0) {
                        foreach($allProducts as $product) {
                            ?>
                            <tr>
                                <td><?php echo $product["id"]; ?></td>
                                <td><?php echo $product["name"]; ?></td>
                                <td><img class="productImage" src="../uploads/<?php echo $product["image"] ?>" alt="<?php echo $product["name"]; ?>"></td>
                                <td><?php echo $product["status"] == 1 ? "Visible" : "Hidden"; ?></td>
                                <td>
                                    <div class="actionContainer">
                                        <a href="editProduct.php?id=<?php echo $product["id"]; ?>">View/Edit</a>
                                        <button type="button" class="deleteProductButton" value="<?php echo $product["id"]; ?>">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<h3 class='noCategory'>No Product Found</h3>";
                    }
                ?>
            </table>
        </div>
    </div>
</div>
