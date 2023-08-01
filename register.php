<?php
session_start();
if (isset($_SESSION["auth"])) {
    $_SESSION["message"] = "Register Page, you are already logged in.";
    header("Location: index.php");
    exit();
}

include "includes/header.php";
?>

<style><?php include "userAssets/register.css"; ?></style>
<div class="registerContainer">
    <?php
        if (isset($_SESSION["message"])) {
            ?>
                <div class="alertMessage"><?php echo $_SESSION["message"]; ?></div>
            <?php
            unset($_SESSION["message"]);
        }
    ?>
    <h3>Registration Form</h3>
    <form action="functions/auth.php" method="post">
        <div class="formGroup">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="Name" required="required">
        </div>
        <div class="formGroup">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Email" required="required">
        </div>
        <div class="formGroup">
            <label for="phone">Phone</label>
            <input type="number" name="phone" id="phone" placeholder="Phone" required="required">
        </div>
        <div class="formGroup">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password" required="required">
        </div>
        <div class="formGroup">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required="required">
        </div>
        <div class="formGroup">
            <input type="submit" name="registerButton" value="Register" />
        </div>
    </form>
</div>

<?php include "includes/footer.php"; ?>
