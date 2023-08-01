<?php 
if(!isset($_SESSION)) {
    session_start(); 
}
?>

<style><?php include "userAssets/footer.css"; ?></style>
<div class="footerContainer">
    <div class="col1">
        <a class="footerNavLink" href="index.php">Home</a>
        <?php if (isset($_SESSION["authUser"])) { ?>
            <a class="footerNavLink" href="cart.php">Cart</a>
            <a class="footerNavLink" href="logout.php">Logout</a>
        <?php } else { ?>
            <a class="footerNavLink" href="login.php">Login</a>
            <a class="footerNavLink" href="register.php">Register</a>
        <?php } ?>
    </div>
    <div class="col2">
        Address
<pre>
    Krishna Raj Puram, Seegahalli,
    Karnataka, The Republic of India

    Phone: +91 - 8123747965
    Email: ash.ranjan09@gmail.com
</pre>
    </div>
<iframe class="col3" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d497700.1123431985!2d77.3012611768966!3d12.953790192004355!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae1670c9b44e6d%3A0xf8dfc3e8517e4fe0!2sBengaluru%2C%20Karnataka!5e0!3m2!1sen!2sin!4v1690837412575!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
</body>
</html>