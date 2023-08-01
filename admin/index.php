<?php
    include "../middleware/adminVerification.php";
?>

<style><?php include("../assets/admin.css"); ?></style>
<div class="adminContainer">
    <div class="header">
        <?php include "adminHeader.php"?>
    </div>
    <div class="contentSection">
        <div class="controlSection">
            <?php include "adminSidebar.php"; ?>
        </div>
        <div class="mainContent">main</div>
    </div>
</div>
