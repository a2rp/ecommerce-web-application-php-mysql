<?php 
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "includes/header.php";
    include "functions/userFunctions.php";
?>

<style><?php include "userAssets/products.css"; ?></style>

<div class="productContainer">
    <?php
        if (isset($_GET["slug"])) {
            $categorySlug = $_GET["slug"];
            $categoryResult = getSlugActive("categories", $categorySlug);
            $category = mysqli_fetch_array($categoryResult);
            if ($category) {
                $categoryId = $category["id"];
                $categoryName = $category["name"];
            ?>
            <h1 class="breadcrumbs"><a href="<?php echo "index.php"; ?>">Home</a> / <a href="<?php echo "categories.php"; ?>">Categories</a> / <?php echo escapeHtml($categoryName); ?></h1>
            <?php
                $products = getProductsByCategoryId($categoryId);
                if (mysqli_num_rows($products)>0) {
                    foreach($products as $product) {
                    ?>
                        <a href="productView.php?product=<?php echo rawurlencode($product["slug"]); ?>" class="listContainer">
                            <img class="image" src="uploads/<?php echo escapeHtml($product["image"]); ?>" alt="<?php echo escapeHtml($product["name"]); ?>" />
                            <div class="text"><?php echo escapeHtml($product["name"]); ?></div>
                        </a>
                    <?php
                    }
                } else {
                    echo "empty";
                }
            ?>
        <?php
            } else {
                echo "Something went wrong";
            }
        } else {
            echo "Something went wrong";
        }
        ?>
</div>

<?php include "includes/footer.php"; ?>
