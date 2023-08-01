<?php
if(!isset($_SESSION)) {
    session_start(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>a2rp</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/header.css">
    <script src="assets/jQuery v3.7.0.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
    <!-- <script>$(document).ready(function(){console.log(1);});</script> -->
</head>
<body>
    <div class="headerContainer">
        <a href="index.php" class="home">Ecommerce Application</a>
        <div class="navbarContainer">
            <!-- <a href="index.php" class="navlink home">Home</a> -->
            <a href="admin/index.php" class="navlink about">Admin Page</a>
            <?php if (isset($_SESSION["auth"])) { ?>
                <!-- <div class="navlink name"><?php // echo $_SESSION["authUser"]["name"]; ?></div> -->
                <!-- <a class="navlink categories" href="categories.php">Categories</a>
                <a class="navlink cart" href="cart.php">Cart</a>
                <a class="navlink logout" href="logout.php">Logout</a> -->
                <div class="dropdown" style="width: 170px;">
                    <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">
                        <?php echo $_SESSION["authUser"]["name"]; ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <li role="presentation">
                            <!-- <a role="menuitem" tabindex="-1" href="#">HTML</a> -->
                            <a role="menuitem" tabindex="-1" class="navlink categories" href="categories.php">Categories</a>
                        </li>
                        <li role="presentation">
                            <!-- <a role="menuitem" tabindex="-1" href="#">CSS</a> -->
                            <a role="menuitem" tabindex="-1" class="navlink categories" href="cart.php">Cart</a>
                        </li>
                        <li role="presentation">
                            <!-- <a role="menuitem" tabindex="-1" href="#">JavaScript</a> -->
                            <a role="menuitem" tabindex="-1" class="navlink categories" href="myOrders.php">My Orders</a>
                        </li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation">
                            <!-- <a role="menuitem" tabindex="-1" href="#">About Us</a> -->
                            <a role="menuitem" tabindex="-1" class="navlink categories" href="logout.php">Log out</a>
                        </li>
                    </ul>
                </div>
            <?php } else { ?>
                <a href="register.php" class="navlink register">Register</a>
                <a href="login.php" class="navlink login">Login</a>
            <?php } ?>
        </div>
    </div>
