<?php
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "../middleware/adminVerification.php";
?>

<style><?php include("../assets/addCategory.css"); ?></style>
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
            <h2>Add Category</h2>
            <form action="adminCode.php" method="post" enctype="multipart/form-data">
                <div class="formContainer">
                    <div class="nameSlugContainer">
                        <div class="formGroup">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" placeholder="Name" required />
                        </div>
                        <div class="formGroup">
                            <label for="slug">Slug</label>
                            <input type="text" id="slug" name="slug" placeholder="Slug" required />
                        </div>
                    </div>
                    <div class="formGroup">
                        <label for="description">Description</label>
                        <textarea rows="3" id="description" name="description" placeholder="Description" required></textarea>
                    </div>
                    <div class="formGroup">
                        <label for="image">Upload Image</label>
                        <input type="file" id="image" name="image" required />
                    </div>
                    <div class="metaContainer">
                        <div class="formGroup">
                            <label for="meta_title">Meta Title</label>
                            <textarea rows="3" id="meta_title" name="meta_title" placeholder="Meta title" required></textarea>
                        </div>
                        <div class="formGroup">
                            <label for="meta_description">Meta Description</label>
                            <textarea rows="3" id="meta_description" name="meta_description" placeholder="Meta Description" required></textarea>
                        </div>
                    </div>
                    <div class="statusPopularContainer">
                        <div class="formGroup statusContainer">
                            <label for="status">Status</label>
                            <input type="checkbox" id="status" name="status" />
                        </div>
                        <div class="formGroup popularContainer">
                            <label for="popular">Popular</label>
                            <input type="checkbox" id="popular" name="popular" />
                        </div>
                    </div>
                    <button type="submit" id="addCategoryButton" name="addCategoryButton" class="addCategoryButton">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
