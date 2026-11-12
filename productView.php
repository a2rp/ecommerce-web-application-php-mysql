<?php 
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "includes/header.php";
    include "functions/userFunctions.php";
?>

<style><?php include "userAssets/productView.css"; ?></style>
<script><?php include "userAssets/js/custom.js"; ?></script>

<div class="productContainer">
    <?php
        if (isset($_GET["product"])) {
            $productSlug = $_GET["product"];
            $productResult = getSlugActive("products", $productSlug);
            $product = mysqli_fetch_array($productResult);

            if ($product) {
                // echo "Product found.";
                ?>
                <div class="breadCrumbs">
                    <h1 class="breadcrumbs"><a href="<?php echo "index.php"; ?>">Home</a> / <a href="<?php echo "categories.php"; ?>">Categories</a> / <?php echo escapeHtml($product["name"]); ?></h1>
                </div>
                <div class="productDetail">
                    <img class="image" src="uploads/<?php echo escapeHtml($product["image"]); ?>" alt="<?php echo escapeHtml($product["name"]); ?>" />
                    <div class="text">
                        <div class="productNameTrending">
                            <div class="productName"><?php echo escapeHtml($product["name"]); ?></div>
                            <div class="productTrending"><?php if ($product["trending"]) echo "Trending"; ?></div>
                        </div>
                        <hr />
                        <div><?php echo escapeHtml($product["small_description"]); ?></div>
                        <div class="priceContainer">
                            <div class="originalPrice">&#8377; <?php echo $product["original_price"]; ?></div>
                            <div class="sellingPrice">&#8377; <?php echo $product["selling_price"]; ?></div>
                        </div>
                        <div class="addToCart">
                            <div class="inputArea">
                                <div class="minus">-</div>
                                <input type="number" name="itemNumber" id="itemNumber" disabled value="1">
                                <div class="plus">+</div>
                            </div>
                            <!-- <div class="repeatOrderContainer">
                                <input class="repeatOrderCheckbox" type="checkbox" /> <span>REPEAT THIS ORDER</span>
                            </div> -->
                            <button name="addToCartButton" class="addToCartButton" value="<?php echo (int) $product["id"]; ?>">Add To Cart</button>
                        </div>
                        <div class="productDescription">
                            <h1>Product Description</h1>
                            <?php echo escapeHtml($product["description"]); ?>
                        </div>
                    </div>
                </div>
                <?php
            }else {
                echo "Product not found.";
            }

        } else {
            echo "Something went wrong";
        }
    ?>
</div>

<?php include "includes/footer.php"; ?>
