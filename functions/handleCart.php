<?php 
if(!isset($_SESSION)) {
    session_start(); 
}
include("../config/connect.php");

if(isset($_SESSION["auth"])) {
    if (isset($_POST["scope"])) {
        $scope = $_POST["scope"];
        if ($scope=="add") {
            $productId = $_POST["productId"];
            $productQuantity = $_POST["productQuantity"];
            $repeatThisOrder = $_POST["repeatThisOrder"];
            $userId = $_SESSION["authUser"]["userId"];

            $verifyExisting = "SELECT * FROM carts WHERE productId = '$productId' AND userId = '$userId'";
            $verifyExistingResult = mysqli_query($conn, $verifyExisting);
            // if (mysqli_num_rows($verifyExistingResult)>0 && $repeatThisOrder=="false") {
            if (mysqli_num_rows($verifyExistingResult)>0) {
                echo "existing";
            } else {
                $insertQuery = "INSERT INTO carts (userId, productId, productQuantity) VALUES ('$userId', '$productId', '$productQuantity')";
                $result = mysqli_query($conn, $insertQuery);
                if ($result) {
                    echo 201; // status okay and new record created
                } else {
                    echo 500;
                }
            }
        } else if($scope=="update") {
            $productId = $_POST["productId"];
            $productQuantity = $_POST["productQuantity"];
            $userId = $_SESSION["authUser"]["userId"];
            $checkExisting = "SELECT * FROM carts WHERE userId = '$userId' AND productId = '$productId'";
            $checkExistingResult = mysqli_query($conn, $checkExisting);
            if (mysqli_num_rows($checkExistingResult)>0) {
                $updateQuery = "UPDATE carts SET productQuantity='$productQuantity' WHERE productId='$productId' AND userId = '$userId'";
                $updateResult = mysqli_query($conn, $updateQuery);
                if ($updateResult) {
                    echo 200;
                } else {
                    echo 500;
                }
            }
        } else if($scope=="delete") {
            $cartId = $_POST["cartId"];
            $userId = $_SESSION["authUser"]["userId"];
            $checkExisting = "SELECT * FROM carts WHERE id='$cartId' AND userId='$userId'";
            $checkExistingResult = mysqli_query($conn, $checkExisting);
            if (mysqli_num_rows($checkExistingResult)>0) {
                $deleteQuery = "DELETE FROM carts WHERE id='$cartId'";
                $deleteResult = mysqli_query($conn, $deleteQuery);
                if ($deleteResult) {
                    echo 200;
                } else {
                    echo 500;
                }
            }
        } else {
            echo 500;
        }
    }
} else {
    echo 401; // unauthorized
}
?>