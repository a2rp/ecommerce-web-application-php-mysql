<?php 
    if(!isset($_SESSION)) {
        session_start(); 
    }
    include "includes/header.php";
    include "functions/userFunctions.php";
?>

<style><?php include "userAssets/checkout.css"; ?></style>
<script><?php include "userAssets/js/custom.js"; ?></script>

<div class="checkoutContainer">
    <div class="breadCrumbsContainer">
        <h1 class="breadcrumbs">
            <a href="<?php echo "index.php"; ?>">Home</a>
            / 
            <a href="<?php echo "cart.php"; ?>">Checkout</a>
        </h1>
    </div>
    <div class="billingCartsContainer">
        <div class="billingContainer">
            <div class="billingDetailsHeader">Billing Details</div>
            <form action="functions/placeorder.php" method="POST">
                <div class="formGroup nameEmailContainer">
                    <div class="nameContainer">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" placeholder="Name" required />
                    </div>
                    <div class="emailContainer">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email" required />
                    </div>
                </div>
                <div class="formGroup phonePincodeContainer">
                    <div class="phoneContainer">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" placeholder="Phone" required />
                    </div>
                    <div class="pincodeContainer">
                        <label for="pincode">Pin Code</label>
                        <input type="text" name="pincode" id="pincode" placeholder="Pin Code" required />
                    </div>
                </div>
                <div class="addressContainer">
                    <label for="address">Address</label>
                    <textarea rows="5" name="address" id="address" placeholder="Address" required></textarea>
                </div>
                <input type="hidden" name="paymentMode" value="COD" />
                <button type="submit" name="placeOrderButton" id="placeOrderButton" class="placeOrder">Confirm and place order | cash on delivery</button>
            </form>
        </div>
        <div class="cartsContentContainer">
            <div class="cartItemHeader">Order Details</div>
            <?php
                $cartItems = getCartItems();
                $totalPrice = 0;
                foreach ($cartItems as $item) {
                    // print_r($item);
                ?>
                    <div class="cartItemContainer">
                        <div class="image"><img src="uploads/<?php echo $item["image"]; ?>" alt="item image" /></div>
                        <div class="name"><?php echo $item["name"]; ?></div>
                        <div class="price">&#8377; <?php echo $item["selling_price"]; ?></div>
                        <div class="numberOfItems">x <?php echo $item["productQuantity"]; ?></div>
                    </div>
                <?php
                $totalPrice += $item["selling_price"] * $item["productQuantity"];
                }
            ?>
            <hr />
            <a href="cart.php" style="margin-top: 15px; display: block;">Edit Items</a>
            <hr />
            <div class="totalPrice">
                <h3>Total Price</h3>: <h3>&#8377; <?= $totalPrice ?></h3>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
