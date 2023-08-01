<?php
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "../middleware/adminVerification.php";
?>

<style><?php include("../assets/editCategory.css"); ?></style>
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
        <?php if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $category = getById("categories", $id);
            if (mysqli_num_rows($category)>0) {
                $data = mysqli_fetch_array($category);
        ?>
            <div class="mainContent">
                <h2 class="headingContainer">
                    <div class="heading">Edit Category</div>
                    <a class="allCategoryLink" href="../admin/category.php">ALL CATEGORIES</a>
                </h2>
                <form action="adminCode.php" method="post" enctype="multipart/form-data">
                    <div class="formContainer">
                        <div class="nameSlugContainer">
                            <div class="formGroup">
                                <input type="hidden" id="categoryId" name="categoryId" value = "<?php echo $data['id']; ?>" />
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" value="<?php echo $data['name'];?>" placeholder="Name" />
                            </div>
                            <div class="formGroup">
                                <label for="slug">Slug</label>
                                <input type="text" id="slug" name="slug" value="<?php echo $data['slug'];?>" placeholder="Slug" />
                            </div>
                        </div>
                        <div class="formGroup">
                            <label for="description">Description</label>
                            <textarea rows="3" id="description" name="description" placeholder="Description"><?php echo $data['description'];?></textarea>
                        </div>
                        <div class="formGroup">
                            <label for="image">Upload Image</label>
                            <input type="file" id="image" name="image" />
                            <label for="" style="margin-top: 5px;">Present Image</label>
                            <input type="hidden" name="oldImage" value="<?php echo $data['image']; ?>" />
                            <img src="../uploads/<?php echo $data['image']; ?>" alt="<?php echo $data['image']; ?>" style="width: 50px; height: 50px;" />
                        </div>
                        <div class="metaContainer">
                            <div class="formGroup">
                                <label for="meta_title">Meta Title</label>
                                <textarea rows="3" id="meta_title" name="meta_title" placeholder="Meta title"><?php echo $data['meta_title'];?></textarea>
                            </div>
                            <div class="formGroup">
                                <label for="meta_description">Meta Description</label>
                                <textarea rows="3" id="meta_description" name="meta_description" value="<?php echo $data['meta_description'];?>" placeholder="Meta Description"><?php echo $data['meta_description'];?></textarea>
                            </div>
                        </div>
                        <div class="statusPopularContainer">
                            <div class="formGroup statusContainer">
                                <label for="status">Status</label>
                                <input type="checkbox" id="status" name="status" <?php echo $data['status'] ? "checked" : ""; ?> />
                            </div>
                            <div class="formGroup popularContainer">
                                <label for="popular">Popular</label>
                                <input type="checkbox" id="popular" name="popular" <?php echo $data['popular'] ? "checked" : ""; ?> />
                            </div>
                        </div>
                        <button type="submit" id="updateCategoryButton" name="updateCategoryButton" class="updateCategoryButton">Update</button>
                    </div>
                </form>
            </div>
        <?php
            } else {
                echo "<h3 style='width: 100%; margin-top: 15px; text-align: center;'>Category Not Found.</h3>";
            }
        } else {
            echo "<h3 style='width: 100%; margin-top: 15px; text-align: center;'>Id Not Found In URL.</h3>";
        } ?>
    </div>
</div>
