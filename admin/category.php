<?php
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "../middleware/adminVerification.php";
?>

<script><?php include("../assets/jQuery v3.7.0.js"); ?></script>
<style><?php include("../assets/category.css"); ?></style>
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
                <div class="allCategoriesInfo">All Categories</div>
                <a class="newCategoryLink" href="../admin/addCategory.php">ADD NEW CATEGORY</a>
            </h2>
            <table class="categoryTable">
                <thead>
                    <tr>
                        <th>Id</th><th>Name</th><th>Image</th><th>Status</th><th>Action</th>
                    </tr>
                </thead>
                <?php
                    $allCategories = getAll("categories");
                    if (mysqli_num_rows($allCategories)>0) {
                        foreach($allCategories as $category) {
                            ?>
                            <tr>
                                <td><?php echo $category["id"]; ?></td>
                                <td><?php echo $category["name"]; ?></td>
                                <td><img class="categoryImage" src="../uploads/<?php echo $category["image"] ?>" alt="<?php echo $category["name"]; ?>"></td>
                                <td><?php echo $category["status"] == 0 ? "Visible" : "Hidden"; ?></td>
                                <td>
                                    <div class="actionContainer">
                                        <a href="editCategory.php?id=<?php echo $category["id"]; ?>">View/Edit</a>
                                        <!-- <form action="adminCode.php" method="post">
                                            <input type="hidden" name="categoryId" id="categoryId" value="<?php // echo $category["id"]; ?>" />
                                            <button type="submit" class="deleteButton" id="deleteCategoryButton" name="deleteCategoryButton">Delete</button>
                                        </form> -->
                                        <button type="button" class="deleteCategoryButton" value="<?php echo $category["id"]; ?>">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<h3 class='noCategory'>No Category Found</h3>";
                    }
                ?>
            </table>
        </div>
    </div>
</div>
