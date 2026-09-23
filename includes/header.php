<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#202529">
    <meta name="description" content="A PHP and MySQL ecommerce application with customer and admin workflows.">
    <meta property="og:title" content="Ecommerce Application">
    <meta property="og:description" content="Browse products, manage a cart, and review orders in a PHP and MySQL ecommerce application.">
    <meta property="og:image" content="https://raw.githubusercontent.com/a2rp/ecommerce-web-application-php-mysql/main/assets/images/preview.png">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <title>Ecommerce Application</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="assets/jQuery v3.7.0.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <header class="headerContainer">
        <a href="index.php" class="home" aria-label="Ecommerce Application home">
            <img src="assets/images/logo.png" alt="Ashish Ranjan logo">
            <span>Ecommerce Application</span>
        </a>
        <button class="menuToggle" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="siteNavigation">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>
        <nav class="navbarContainer" id="siteNavigation" aria-label="Primary navigation">
            <a href="admin/index.php" class="navlink about">Admin Page</a>
            <?php if (isset($_SESSION["auth"])) { ?>
                <div class="dropdown accountMenu">
                    <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION["authUser"]["name"], ENT_QUOTES, "UTF-8"); ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="categories.php">Categories</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="cart.php">Cart</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="myOrders.php">My Orders</a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="logout.php">Log out</a></li>
                    </ul>
                </div>
            <?php } else { ?>
                <a href="register.php" class="navlink register">Register</a>
                <a href="login.php" class="navlink login">Login</a>
            <?php } ?>
        </nav>
    </header>
    <script>
        const menuToggle = document.querySelector(".menuToggle");
        const siteNavigation = document.querySelector("#siteNavigation");
        menuToggle?.addEventListener("click", () => {
            const isOpen = siteNavigation.classList.toggle("isOpen");
            menuToggle.setAttribute("aria-expanded", String(isOpen));
        });
    </script>
