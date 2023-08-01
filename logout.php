<?php

session_start();

if (isset($_SESSION{"auth"})) {
    unset($_SESSION["auth"]);
    unset($_SESSION["authUser"]);
    $_SESSION["message"] = "Logged out successfully";
}
header("Location: index.php");

?>
