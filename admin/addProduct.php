<?php
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "../middleware/adminVerification.php";
?>

<style><?php include("../assets/addProduct.css"); ?></style>
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
            <h2>Add Product</h2>
            <form action="adminCode.php" method="post" enctype="multipart/form-data">
                <div class="formGroup">
                    <label for="">Select Category</label>
                    <select name="categoryId" id="categoryId" class="categoryId" required>
                        <option selected disabled>Select Category</option>
                        <?php
                            $categories = getAll("categories");
                            if (mysqli_num_rows($categories)>0) {
                                foreach ($categories as $category) {
                                    ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category["name"]; ?></option>
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
                        <input type="text" id="name" name="name" placeholder="Name" value="product<?= rand (1,9999)?>" required />
                    </div>
                    <div class="formGroup">
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" name="slug" placeholder="Slug" value="slug<?= rand (1,9999)?>" required />
                    </div>
                </div>
                <div class="descriptionContainer">
                    <div class="formGroup">
                        <label for="description">Small Description</label>
                        <textarea rows="3" id="smallDescription" name="smallDescription" placeholder="Small Description" required>smallDesc<?= rand (1,9999)?></textarea>
                    </div>
                    <div class="formGroup">
                        <label for="description">Description</label>
                        <textarea rows="3" id="description" name="description" placeholder="Description" required>bigDesc<?= rand (1,9999)?></textarea>
                    </div>
                </div>
                <div class="priceContainer">
                    <div class="formGroup">
                        <label for="originalPrice">Original Price</label>
                        <input type="number" id="originalPrice" name="originalPrice" placeholder="Original Price" required value="<?= rand (1,9999)?>" />
                    </div>
                    <div class="formGroup">
                        <label for="sellingPrice">Selling Price</label>
                        <input type="number" id="sellingPrice" name="sellingPrice" placeholder="Selling Price" required value="<?= rand (1,9999)?>" />
                    </div>
                </div>
                <div class="imageQuantityContainer">
                    <div class="formGroup">
                        <label for="image">Upload Image</label>
                        <input type="file" id="image" name="image" required />
                    </div>
                    <div class="formGroup">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" placeholder="Quantity" required value="<?= rand (1,9999)?>" />
                    </div>
                </div>
                <div class="metaContainer">
                    <div class="formGroup">
                        <label for="meta_title">Meta Title</label>
                        <textarea rows="3" id="metaTitle" name="metaTitle" placeholder="Meta title" required>value="metaTitle<?= rand (1,9999)?>"</textarea>
                    </div>
                    <div class="formGroup">
                        <label for="metaDescription">Meta Description</label>
                        <textarea rows="3" id="metaDescription" name="metaDescription" placeholder="Meta Description" required> value="metaDesc<?= rand (1,9999)?>"</textarea>
                    </div>
                    <div class="formGroup">
                        <label for="metaKeywords">Meta Keyword</label>
                        <textarea rows="3" id="metaKeywords" name="metaKeywords" placeholder="Meta keywords" required>value="metakeywords<?= rand (1,9999)?>"</textarea>
                    </div>
                </div>
                <div class="statusTrendingContainer">
                    <div class="formGroup statusContainer">
                        <label for="status">Status</label>
                        <input type="checkbox" id="status" name="status" />
                    </div>
                    <div class="formGroup trendingContainer">
                        <label for="trending">Trending</label>
                        <input type="checkbox" id="trendng" name="trending" />
                    </div>
                </div>
                <button type="submit" id="addProductButton" name="addProductButton" class="addProductButton">Save Product</button>
            </form>
        </div>
    </div>
</div>
