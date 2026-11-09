<?php
session_start();
include "../config/connect.php";
include "../functions/myFunctions.php";
require_once "../functions/imageUpload.php";
require_once "../functions/csrf.php";

if (!isset($_SESSION["auth"], $_SESSION["role"]) || (int) $_SESSION["role"] !== 1) {
    http_response_code(403);
    exit("Forbidden");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}
if (!verifyCsrfToken()) {
    http_response_code(403);
    exit("Invalid request token.");
}

function adminField($key) {
    global $conn;
    return mysqli_real_escape_string($conn, trim((string) ($_POST[$key] ?? "")));
}

function adminImagePath($fileName) {
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . basename($fileName);
}

if (isset($_POST['addCategoryButton'])) {
    $name = adminField("name");
    $slug = adminField("slug");
    $description = adminField("description");
    $meta_title = adminField("meta_title");
    $meta_description = adminField("meta_description");
    $meta_keywords = adminField("meta_keywords");
    $status = isset($_POST["status"]) ? 1 : 0;
    $popular = isset($_POST["popular"]) ? 1 : 0;
    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";
    $fileName = storeUploadedImage($_FILES["image"] ?? null, $path);
    if ($fileName === null || $fileName === false) {
        redirect("Choose a valid image up to 5 MB.", "addCategory.php");
    }

    $createQuery = "INSERT INTO categories (name, slug, description, meta_title, meta_description,  meta_keywords, status, popular, image)  VALUES ('$name', '$slug', '$description', '$meta_title', '$meta_description',  '$meta_keywords', '$status', '$popular', '$fileName')";
    $result = mysqli_query($conn, $createQuery);
    if ($result) {
        redirect("Category Added Successfully", "addCategory.php");
    } else {
        @unlink(adminImagePath($fileName));
        redirect("Something went wrong", "addCategory.php");
    }
} else if (isset($_POST['updateCategoryButton'])) {
    $name = adminField("name");
    $slug = adminField("slug");
    $description = adminField("description");
    $meta_title = adminField("meta_title");
    $meta_description = adminField("meta_description");
    $meta_keywords = adminField("meta_keywords");
    $status = isset($_POST["status"]) ? 1 : 0;
    $popular = isset($_POST["popular"]) ? 1 : 0;

    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";
    $oldImage = basename((string) ($_POST["oldImage"] ?? ""));
    $newImage = storeUploadedImage($_FILES["image"] ?? null, $path);
    if ($newImage === false) {
        redirect("Choose a valid image up to 5 MB.", "editCategory.php?id=" . (int) ($_POST["categoryId"] ?? 0));
    }
    $updateFilename = $newImage ?? $oldImage;

    $categoryId = filter_var($_POST["categoryId"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($categoryId === false) {
        if ($newImage !== null) @unlink(adminImagePath($newImage));
        redirect("Invalid category.", "category.php");
    }
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
        if ($newImage !== null && $oldImage !== "") {
            @unlink(adminImagePath($oldImage));
        }
        redirect("Category Updated Successfully", "editCategory.php?id=$categoryId");
    } else {
        if ($newImage !== null) @unlink(adminImagePath($newImage));
        redirect("Something went wrong", "editCategory.php?id=$categoryId");
    }
} else if (isset($_POST["deleteCategoryButton"])) {
    $categoryId = filter_var($_POST["categoryId"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($categoryId === false) {
        http_response_code(400);
        exit("400");
    }

    $imageStatement = $conn->prepare("SELECT image FROM categories WHERE id = ? LIMIT 1");
    $imageStatement->bind_param("i", $categoryId);
    $imageStatement->execute();
    $imageData = $imageStatement->get_result()->fetch_assoc();
    $imageStatement->close();

    $deleteStatement = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $deleteStatement->bind_param("i", $categoryId);
    $deleteResult = $deleteStatement->execute();
    $deleteStatement->close();

    if ($deleteResult) {
        if ($imageData && $imageData["image"] !== "") {
            @unlink(adminImagePath($imageData["image"]));
        }
        echo 200;
    } else {
        // redirect("Something went wrong", "category.php");
        echo 500;
    }
} else if (isset($_POST['addProductButton'])) {
    $categoryId = filter_var($_POST["categoryId"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $name = adminField("name");
    $slug = adminField("slug");
    $small_description = adminField("smallDescription");
    $description = adminField("description");
    $rawOriginalPrice = trim((string) ($_POST["originalPrice"] ?? ""));
    $rawSellingPrice = trim((string) ($_POST["sellingPrice"] ?? ""));
    $quantity = filter_var($_POST["quantity"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]]);
    if ($categoryId === false || $quantity === false || !is_numeric($rawOriginalPrice) || !is_numeric($rawSellingPrice) || (float) $rawOriginalPrice < 0 || (float) $rawSellingPrice < 0) {
        redirect("Enter a valid category, price, and quantity.", "addProduct.php");
    }
    $original_price = number_format((float) $rawOriginalPrice, 2, ".", "");
    $selling_price = number_format((float) $rawSellingPrice, 2, ".", "");
    $meta_title = adminField("metaTitle");
    $meta_description = adminField("metaDescription");
    $meta_keywords = adminField("metaKeywords");
    $status = isset($_POST["status"]) ? 1 : 0;
    $trending = isset($_POST["trending"]) ? 1 : 0;

    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";
    $fileName = storeUploadedImage($_FILES["image"] ?? null, $path);
    if ($fileName === null || $fileName === false) {
        redirect("Choose a valid image up to 5 MB.", "addProduct.php");
    }

    $productInsertQuery = "INSERT INTO products
    (category_id, name, slug, small_description, description, original_price, selling_price, image, quantity, status, trending, meta_title, meta_keywords, meta_description)
    VALUES
    ('$categoryId', '$name', '$slug', '$small_description', '$description', '$original_price', '$selling_price', '$fileName', '$quantity', '$status', '$trending', '$meta_title', '$meta_keywords', '$meta_description')";
    $result = mysqli_query($conn, $productInsertQuery);
    if ($result) {
        redirect("Product Added Successfully", "addProduct.php");
    } else {
        @unlink(adminImagePath($fileName));
        redirect("Something went wrong", "addProduct.php");
    }
} else if (isset($_POST["updateProductButton"])) {
    $productId = filter_var($_POST["productId"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $categoryId = filter_var($_POST["categoryId"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $name = adminField("name");
    $slug = adminField("slug");
    $small_description = adminField("smallDescription");
    $description = adminField("description");
    $rawOriginalPrice = trim((string) ($_POST["originalPrice"] ?? ""));
    $rawSellingPrice = trim((string) ($_POST["sellingPrice"] ?? ""));
    $quantity = filter_var($_POST["quantity"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]]);
    if ($productId === false || $categoryId === false || $quantity === false || !is_numeric($rawOriginalPrice) || !is_numeric($rawSellingPrice) || (float) $rawOriginalPrice < 0 || (float) $rawSellingPrice < 0) {
        redirect("Enter a valid product, category, price, and quantity.", "product.php");
    }
    $original_price = number_format((float) $rawOriginalPrice, 2, ".", "");
    $selling_price = number_format((float) $rawSellingPrice, 2, ".", "");
    $meta_title = adminField("metaTitle");
    $meta_description = adminField("metaDescription");
    $meta_keywords = adminField("metaKeywords");
    $status = isset($_POST["status"]) ? 1 : 0;
    $trending = isset($_POST["trending"]) ? 1 : 0;

    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";
    $oldImage = basename((string) ($_POST["oldImage"] ?? ""));
    $newImage = storeUploadedImage($_FILES["image"] ?? null, $path);
    if ($newImage === false) {
        redirect("Choose a valid image up to 5 MB.", "editProduct.php?id=" . $productId);
    }
    $updateFilename = $newImage ?? $oldImage;

    $updateProductQuery = "UPDATE products SET category_id='$categoryId', name='$name', small_description='$small_description', description='$description', original_price='$original_price', selling_price='$selling_price', image='$updateFilename', quantity='$quantity', status='$status', trending='$trending', meta_title='$meta_title', meta_keywords='$meta_keywords', meta_description='$meta_description', slug='$slug' WHERE id='$productId'";
    $updateProductResult = mysqli_query($conn, $updateProductQuery);
    if ($updateProductResult) {
        if ($newImage !== null && $oldImage !== "") {
            @unlink(adminImagePath($oldImage));
        }
        redirect("Product Updated Successfully", "editProduct.php?id=$productId");
    } else {
        if ($newImage !== null) @unlink(adminImagePath($newImage));
        redirect("Something went wrong", "editProduct.php?id=$productId");
    }
} else if (isset($_POST["deleteProductButton"])) {
    $productId = filter_var($_POST["productId"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($productId === false) {
        http_response_code(400);
        exit("400");
    }

    $imageStatement = $conn->prepare("SELECT image FROM products WHERE id = ? LIMIT 1");
    $imageStatement->bind_param("i", $productId);
    $imageStatement->execute();
    $imageData = $imageStatement->get_result()->fetch_assoc();
    $imageStatement->close();

    $deleteStatement = $conn->prepare("DELETE FROM products WHERE id = ?");
    $deleteStatement->bind_param("i", $productId);
    $deleteResult = $deleteStatement->execute();
    $deleteStatement->close();

    if ($deleteResult) {
        if ($imageData && $imageData["image"] !== "") {
            @unlink(adminImagePath($imageData["image"]));
        }
        echo 200;
    } else {
        // redirect("Something went wrong", "category.php");
        echo 500;
    }
} else if (isset($_POST["updateStatusButton"])) {
    $trackingNumber = trim((string) ($_POST["trackingNumber"] ?? ""));
    $orderStatus = filter_var($_POST["orderStatus"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 2]]);
    if ($trackingNumber === "" || strlen($trackingNumber) > 64 || $orderStatus === false) {
        redirect("Invalid order status update.", "orders.php");
    }

    $updateStatement = $conn->prepare("UPDATE orders SET status = ? WHERE trackingNumber = ?");
    $updateStatement->bind_param("is", $orderStatus, $trackingNumber);
    $result = $updateStatement->execute();
    $updateStatement->close();
    redirect($result ? "Updated successfully." : "Could not update order status.", "viewOrder.php?trackingNumber=" . rawurlencode($trackingNumber));
} else {
    header("Location: ../index.php");
}
?>
