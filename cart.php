<?php 
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "includes/header.php";
    include "functions/userFunctions.php";
?>

<style><?php include "userAssets/cart.css"; ?></style>
<script><?php include "userAssets/js/custom.js"; ?></script>

<div class="cartContainer">
    <h1 class="breadcrumbs">
        <a href="<?php echo "index.php"; ?>">Home</a>
        / 
        <a href="<?php echo "cart.php"; ?>">Cart</a>
        --&gt;
        <a href="myOrders.php">My Orders</a>
    </h1>
    <div class="cartsContentContainer">
        <div class="cartItemHeader">
            <b>Product</b>
            <b></b>
            <b>Price</b>
            <b>Quantity</b>
            <b>Remove</b>
        </div>
        <?php
            $cartItems = getCartItems();
            // print_r($cartItems);
            if (mysqli_num_rows($cartItems)>0) {
                foreach ($cartItems as $item) {
                    // print_r($item);
                ?>
                    <div class="cartItemContainer">
                        <div class="image"><img src="uploads/<?php echo $item["image"]; ?>" alt="item image" /></div>
                        <div class="name"><?php echo $item["name"]; ?></div>
                        <div class="price"><?php echo $item["selling_price"]; ?></div>
                        <div class="incrementDecrement">
                            <input type="hidden" class="productId" name="productId" value="<?php echo $item["productId"]; ?>"/>
                            <div class="inputArea">
                                <div class="decrement minus updateQuantity">-</div>
                                <input class="itemNumber" type="number" value="<?php echo $item["productQuantity"]; ?>" disabled />
                                <div class="increment plus updateQuantity">+</div>
                            </div>
                        </div>
                        <div class="remove">
                            <button class="removeButton deleteItem" value="<?php echo $item['cartId']; ?>">Remove</button>
                        </div>
                    </div>
                <?php
                }
            ?>
            <a class="checkout" href="checkout.php">Proceed to Checkout</a>
            <?php 
            } else { ?>
                <h1 style="font-size: 20px; text-align: center;">No items in yout cart</h1>
            <?php } ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>
