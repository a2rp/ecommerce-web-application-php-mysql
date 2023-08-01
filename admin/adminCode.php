<?php
session_start();
include "../config/connect.php";
include "../functions/myFunctions.php";

if (isset($_POST['addCategoryButton'])) {
    $name = $_POST["name"];
    $slug = $_POST["slug"];
    $description = $_POST["description"];
    $meta_title = $_POST["meta_title"];
    $meta_description = $_POST["meta_description"];
    $meta_keywords = $_POST["meta_keywords"];
    $status = isset($_POST["status"]) ? 1 : 0;
    $popular = isset($_POST["popular"]) ? 1 : 0;
    $image = $_FILES["image"]["name"];
    $path = "../uploads";
    $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
    $fileName = time() . "." . $imageExtension;

    $createQuery = "INSERT INTO categories (name, slug, description, meta_title, meta_description,  meta_keywords, status, popular, image)  VALUES ('$name', '$slug', '$description', '$meta_title', '$meta_description',  '$meta_keywords', '$status', '$popular', '$fileName')";
    $result = mysqli_query($conn, $createQuery);
    if ($result) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $path."/".$fileName);
        redirect("Category Added Successfully", "addCategory.php");
    } else {
        redirect("Something went wrong", "addCategory.php");
    }
} else if (isset($_POST['updateCategoryButton'])) {
    $name = $_POST["name"];
    $slug = $_POST["slug"];
    $description = $_POST["description"];
    $meta_title = $_POST["meta_title"];
    $meta_description = $_POST["meta_description"];
    $meta_keywords = $_POST["meta_keywords"];
    $status = isset($_POST["status"]) ? 1 : 0;
    $popular = isset($_POST["popular"]) ? 1 : 0;

    $newImage = $_FILES["image"]["name"];
    $oldImage = $_POST["oldImage"];
    if ($newImage!=="") {
        // $updateFilename = $newImage;
        $imageExtension = pathinfo($newImage, PATHINFO_EXTENSION);
        $updateFilename = time() . "." . $imageExtension;
    } else {
        $updateFilename = $oldImage;
    }
    $path = "../uploads";

    $categoryId = $_POST["categoryId"];
    $updateQuery = "UPDATE categories SET 
    name ='$name', 
    slug='$slug', 
    description='$description', 
    meta_title='$meta_title', 
    meta_description='$meta_description',  
    meta_keywords = '$meta_keywords', 
    status = '$status', 
    popular = '$popular', 
    image = '$updateFilename' 
    WHERE 
    id='$categoryId'";

    $updateQueryResult = mysqli_query($conn, $updateQuery);
    if ($updateQueryResult) {
        if ($_FILES["image"]["name"] != "") {
            // move_uploaded_file($_FILES["image"]["tmp_name"], $path."/".$newImage);
            move_uploaded_file($_FILES["image"]["tmp_name"], $path."/".$updateFilename);
            if (file_exists($path."/".$oldImage)) {
                unlink($path."/".$oldImage);
            }
        }
        redirect("Category Updated Successfully", "editCategory.php?id=$categoryId");
    } else {
        redirect("Something went wrong", "editCategory.php?id=$categoryId");
    }
} else if (isset($_POST["deleteCategoryButton"])) {
    $categoryId = mysqli_real_escape_string($conn, $_POST["categoryId"]);

    $imageQuery = "SELECT * FROM categories WHERE id='$categoryId'";
    $imageResult = mysqli_query($conn, $imageQuery);
    $imageData = mysqli_fetch_array($imageResult);
    $image = $imageData["image"];

    $deleteQuery = "DELETE FROM categories WHERE id='$categoryId'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        if(file_exists("../uploads/".$image)) {
            unlink("../uploads/".$image);
        }
        // redirect("Category deleted successfully", "category.php");
        echo 200;
    } else {
        // redirect("Something went wrong", "category.php");
        echo 500;
    }
} else if (isset($_POST['addProductButton'])) {
    $categoryId = $_POST['categoryId'];

    $name = $_POST["name"];
    $slug = $_POST["slug"];
    $small_description = $_POST["smallDescription"];
    $description = $_POST["description"];
    $original_price = $_POST["originalPrice"];
    $selling_price = $_POST["sellingPrice"];
    $quantity = $_POST["quantity"];
    $meta_title = $_POST["metaTitle"];
    $meta_description = $_POST["metaDescription"];
    $meta_keywords = $_POST["metaKeywords"];
    $status = isset($_POST["status"]) ? 1 : 0;
    $trending = isset($_POST["trending"]) ? 1 : 0;

    $image = $_FILES["image"]["name"];
    $path = "../uploads";
    $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
    $fileName = time() . "." . $imageExtension;

    $productInsertQuery = "INSERT INTO products 
    (category_id, name, slug, small_description, description, original_price, selling_price, image, quantity, status, trending, meta_title, meta_keywords, meta_description) 
    VALUES 
    ('$categoryId', '$name', '$slug', '$small_description', '$description', '$original_price', '$selling_price', '$fileName', '$quantity', '$status', '$trending', '$meta_title', '$meta_keywords', '$meta_description')";
    $result = mysqli_query($conn, $productInsertQuery);
    if ($result) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $path."/".$fileName);
        redirect("Product Added Successfully", "addProduct.php");
    } else {
        redirect("Something went wrong", "addProduct.php");
    }
} else if (isset($_POST["updateProductButton"])) {
    $productId = $_POST['productId'];
    $categoryId = $_POST['categoryId'];

    $name = $_POST["name"];
    $slug = $_POST["slug"];
    $small_description = $_POST["smallDescription"];
    $description = $_POST["description"];
    $original_price = $_POST["originalPrice"];
    $selling_price = $_POST["sellingPrice"];
    $quantity = $_POST["quantity"];
    $meta_title = $_POST["metaTitle"];
    $meta_description = $_POST["metaDescription"];
    $meta_keywords = $_POST["metaKeywords"];
    $status = isset($_POST["status"]) ? 1 : 0;
    $trending = isset($_POST["trending"]) ? 1 : 0;

    $path = "../uploads";
    $newImage = $_FILES["image"]["name"];
    $oldImage = $_POST["oldImage"];
    if ($newImage!=="") {
        // $updateFilename = $newImage;
        $imageExtension = pathinfo($newImage, PATHINFO_EXTENSION);
        $updateFilename = time() . "." . $imageExtension;
    } else {
        $updateFilename = $oldImage;
    }
    $path = "../uploads";

    $updateProductQuery = "UPDATE products SET category_id='$categoryId', name='$name', small_description='$small_description', description='$description', original_price='$original_price', selling_price='$selling_price', image='$updateFilename', quantity='$quantity', status='$status', trending='$trending', meta_title='$meta_title', meta_keywords='$meta_keywords', meta_description='$meta_description', slug='$slug' WHERE id='$productId'";
    $updateProductResult = mysqli_query($conn, $updateProductQuery);
    if ($updateProductResult) {
        if ($_FILES["image"]["name"] != "") {
            // move_uploaded_file($_FILES["image"]["tmp_name"], $path."/".$newImage);
            move_uploaded_file($_FILES["image"]["tmp_name"], $path."/".$updateFilename);
            if (file_exists($path."/".$oldImage)) {
                unlink($path."/".$oldImage);
            }
        }
        redirect("Product Updated Successfully", "editProduct.php?id=$productId");
    } else {
        redirect("Something went wrong", "editProduct.php?id=$productId");
    }
} else if (isset($_POST["deleteProductButton"])) {
    $productId = mysqli_real_escape_string($conn, $_POST["productId"]);

    $imageQuery = "SELECT * FROM products WHERE id='$productId'";
    $imageResult = mysqli_query($conn, $imageQuery);
    $imageData = mysqli_fetch_array($imageResult);
    $image = $imageData["image"];

    $deleteQuery = "DELETE FROM products WHERE id='$productId'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        if(file_exists("../uploads/".$image)) {
            unlink("../uploads/".$image);
        }
        // redirect("Category deleted successfully", "category.php");
        echo 200;
    } else {
        // redirect("Something went wrong", "category.php");
        echo 500;
    }
} else if (isset($_POST["updateStatusButton"])) {
    $trackingNumber = $_POST["trackingNumber"];
    $orderStatus = $_POST["orderStatus"];
    echo $trackingNumber . " . " . $orderStatus;

    $updateOrderStatusQuery = "UPDATE orders SET status = '$orderStatus' WHERE trackingNumber='$trackingNumber'";
    $result = mysqli_query($conn, $updateOrderStatusQuery);
    redirect("Updated successfully", "viewOrder.php?trackingNumber=$trackingNumber");
} else {
    header("Location: ../index.php");
}
?>
