<?php
session_start();
if (isset($_SESSION["auth"])) {
    $_SESSION["message"] = "Loginpage, you are already logged in.";
    header("Location: index.php");
    exit();
}

include "includes/header.php";
?>

<style><?php include "userAssets/login.css"; ?></style>
<div class="loginContainer">
    <?php
        if (isset($_SESSION["message"])) {
            ?>
                <div class="alertMessage"><?php echo $_SESSION["message"]; ?></div>
            <?php
            unset($_SESSION["message"]);
        }
    ?>
    <h3>Login Form</h3>
    <form action="functions/auth.php" method="post">
        <div class="formGroup">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Email" required="required">
        </div>
        <div class="formGroup">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password" required="required">
        </div>
        <div class="formGroup">
            <input type="submit" name="loginButton" value="Login" />
        </div>
    </form>
</div>

<?php include "includes/footer.php"; ?>
