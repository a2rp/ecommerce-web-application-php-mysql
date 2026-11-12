<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    include "../middleware/adminVerification.php";
?>

<style><?php include("../assets/editProduct.css"); ?></style>
<div class="adminContainer">
    <div class="header">
        <?php include "adminHeader.php"?>
    </div>

    <?php if (isset($_SESSION["message"])) { ?>
    <div class="alertMessage"><?php echo escapeHtml($_SESSION["message"]); ?></div>
    <script><?php include("../assets/jQuery v3.7.0.js"); ?></script>
    <script>$(".alertMessage").show().delay(5000).fadeOut();</script>
    <?php } unset($_SESSION["message"]); ?>

    <div class="contentSection">
        <div class="controlSection">
            <?php include "adminSidebar.php"; ?>
        </div>
        <?php
            if (isset($_GET["id"])) {
                $id = $_GET["id"];
                $product = getById("products", $id);
                if (mysqli_num_rows($product)>0) {
                    $data = mysqli_fetch_array($product);
                    ?>
                        <div class="mainContent">
                            <h2 class="headingContainer">
                                <div class="heading">Edit Product</div>
                                <a class="allProductLink" href="../admin/product.php">ALL PRODUCTS</a>
                            </h2>
                            <form action="adminCode.php" method="post" enctype="multipart/form-data">
                                <?php csrfInput(); ?>
                                <input type="hidden" name="productId" value="<?php echo (int) $data['id']; ?>" />
                                <div class="formGroup">
                                    <label for="">Select Category</label>
                                    <select name="categoryId" id="categoryId" class="categoryId" required>
                                        <option selected disabled>Select Category</option>
                                        <?php
                                            $categories = getAll("categories");
                                            if (mysqli_num_rows($categories)>0) {
                                                foreach ($categories as $category) {
                                                    ?>
                                                        <option value="<?php echo (int) $category['id']; ?>" <?php echo $category['id']==$data["category_id"] ? "selected" : ""; ?>>
                                                            <?php echo escapeHtml($category["name"]); ?>
                                                        </option>
                                                    <?php
                                                }
                                            } else {
                                                echo "No category available";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="nameSlugContainer">
                                    <div class="formGroup">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" placeholder="Name" value="<?php echo escapeHtml($data['name']); ?>" required />
                                    </div>
                                    <div class="formGroup">
                                        <label for="slug">Slug</label>
                                        <input type="text" id="slug" name="slug" placeholder="Slug" value="<?php echo escapeHtml($data['slug']); ?>" required />
                                    </div>
                                </div>
                                <div class="descriptionContainer">
                                    <div class="formGroup">
                                        <label for="description">Small Description</label>
                                        <textarea rows="3" id="smallDescription" name="smallDescription" placeholder="Small Description" required><?php echo escapeHtml($data['small_description']); ?></textarea>
                                    </div>
                                    <div class="formGroup">
                                        <label for="description">Description</label>
                                        <textarea rows="3" id="description" name="description" placeholder="Description" required><?php echo escapeHtml($data['description']); ?></textarea>
                                    </div>
                                </div>
                                <div class="priceContainer">
                                    <div class="formGroup">
                                        <label for="originalPrice">Original Price</label>
                                        <input type="number" id="originalPrice" name="originalPrice" placeholder="Original Price" required value="<?php echo escapeHtml($data['original_price']); ?>" />
                                    </div>
                                    <div class="formGroup">
                                        <label for="sellingPrice">Selling Price</label>
                                        <input type="number" id="sellingPrice" name="sellingPrice" placeholder="Selling Price" required value="<?php echo escapeHtml($data['selling_price']); ?>" />
                                    </div>
                                </div>
                                <div class="imageQuantityContainer">
                                    <div class="formGroup">
                                        <label for="image">Upload Image</label>
                                        <input type="hidden" name="oldImage" value="<?php echo escapeHtml($data['image']); ?>" />
                                        <input type="file" id="image" name="image" />
                                        <img src="../uploads/<?php echo escapeHtml($data['image']); ?>" alt="Product Image">
                                    </div>
                                    <div class="formGroup">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" id="quantity" name="quantity" placeholder="Quantity" required value="<?php echo (int) $data['quantity']; ?>" />
                                    </div>
                                </div>
                                <div class="metaContainer">
                                    <div class="formGroup">
                                        <label for="meta_title">Meta Title</label>
                                        <textarea rows="3" id="metaTitle" name="metaTitle" placeholder="Meta title" required><?php echo escapeHtml($data['meta_title']); ?></textarea>
                                    </div>
                                    <div class="formGroup">
                                        <label for="metaDescription">Meta Description</label>
                                        <textarea rows="3" id="metaDescription" name="metaDescription" placeholder="Meta Description" required><?php echo escapeHtml($data['meta_description']); ?></textarea>
                                    </div>
                                    <div class="formGroup">
                                        <label for="metaKeywords">Meta Keyword</label>
                                        <textarea rows="3" id="metaKeywords" name="metaKeywords" placeholder="Meta keywords" required><?php echo escapeHtml($data['meta_keywords']); ?></textarea>
                                    </div>
                                </div>
                                <div class="statusTrendingContainer">
                                    <div class="formGroup statusContainer">
                                        <label for="status">Status</label>
                                        <input type="checkbox" id="status" name="status" <?php echo $data['status'] ? "checked" : ""; ?> />
                                    </div>
                                    <div class="formGroup trendingContainer">
                                        <label for="trending">Trending</label>
                                        <input type="checkbox" id="trendng" name="trending" <?php echo $data['trending'] ? "checked" : ""; ?> />
                                    </div>
                                </div>
                                <button type="submit" id="updateProductButton" name="updateProductButton" class="updateProductButton">Update Product</button>
                            </form>
                        </div>
                    <?php
                } else {
                    echo "<h1>No Product Available</h1>";
                }
            } else {
                echo "<h1>Id Not Found In URL</h1>";
            }
        ?>
    </div>
</div>
