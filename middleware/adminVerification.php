<?php
if(!isset($_SESSION)) {
    session_start(); 
}

include "../functions/myFunctions.php";
if (isset($_SESSION["auth"])) {
    if($_SESSION["role"]!=1) {
        redirect("You cannot access. You do not have admin priviledges.", "../index.php");
    }
} else {
    redirect("Login to continue", "../login.php");
}
?>