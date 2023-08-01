<?php 
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "includes/header.php";
    include "functions/userFunctions.php";
?>

<style><?php include "userAssets/categories.css"; ?></style>

<div class="categoriesContainer">
    <h1 class="categoriesBreadcrumb">
        <a href="<?php echo "index.php"; ?>">Home</a> / Categories
    </h1>
    <?php
        $categories = getAllActive("categories");
        if (mysqli_num_rows($categories)>0) {
            foreach($categories as $category) {
            ?>
                <div class="categoryContainer">
                    <img class="image" src="uploads/<?php echo $category["image"]; ?>" alt="category image">
                    <div class="text">
                        <div><b>Name:</b> <?php echo $category["name"]; ?></div>
                        <div><b>Products under this category:</b> <a href="products.php?slug=<?php echo $category["slug"]; ?>">click here</a></div>
                        <div><b>Description:</b> <?php echo $category["description"]; ?></div>
                        <div><b>Meta Title:</b> <?php echo $category["meta_title"]; ?></div>
                        <div><b>Meta Description:</b> <?php echo $category["meta_description"]; ?></div>
                    </div>
                </div>
            <?php
            }
        } else {
            echo "No items present.";
        }
    ?>
</div>

<?php include "includes/footer.php"; ?>
