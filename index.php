<?php 
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "includes/header.php";
?>

<style><?php include "userAssets/index.css"; ?></style>
<style><?php include "functions/userFunctions.php"; ?></style>

<div class="indexContainer">
    <?php
        if (isset($_SESSION["auth"])) {
            // header("Location: myOrders.php");
            if(isset($_SESSION["message"])) {
                ?>
                    <div class="alertMessage"><?php echo $_SESSION["message"]; ?></div>
                <?php
                unset($_SESSION["message"]);
            }
        }
    ?>

<?php if (isset($_SESSION["auth"])) { ?>
        <div class="navlink name">Welcome <?php echo $_SESSION["authUser"]["name"]; ?></div>
    <?php } else { ?>
        <!-- <a href="register.php" class="navlink register">Register</a> <a href="login.php" class="navlink login">Login</a> to continue -->
    <?php } ?>

    <h1 style="font-size: 30px;">eCommerce Website in PHP and MySQL admin and users</h1>

    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img src="assets/images/cart1.jpg" alt="Cart 1" style="width: 100%; height: 100vh;" />
            </div>

            <div class="item">
                <img src="assets/images/cart2.webp" alt="Cart 2" style="width: 100%; height: 100vh;" />
            </div>
            
            <div class="item">
                <img src="assets/images/cart3.png" alt="Cart 3" style="width: 100%; height: 100vh;" />
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="trendingProducts">
        <div class="heading">Trending</div>
        <div class="detail">
            <?php
            $trendingProducts = getAllTrending();
            // print_r($trendingProducts);
            if (mysqli_num_rows($trendingProducts)>0) {
                foreach ($trendingProducts as $product) {
                ?>
                    <a href="productView.php?product=<?php echo $product["slug"] ?>" class="listContainer">
                        <img class="image" src="uploads/<?php echo $product["image"]; ?>" alt="product image" />
                        <div class="text"><?php echo $product["name"]; ?></div>
                    </a>
                <?php
                }
            }
            ?>
        </div>
    </div>

    <div class="aboutMe">
        <div class="heading">About Me</div>
        <div class="detail">
            I am a versatile web designer due to my extensive history in web design. My dedication to continuously learning about new web design trends and concepts has made me a valuable member of the team. With above seven years of experience and a master's in computer application [MCA], my expertise can help customers modernize with websites and appeal to expanding customer populations.
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
