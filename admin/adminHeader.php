<?php require_once __DIR__ . "/../functions/csrf.php"; ?>
<style><?php include "../assets/adminHeader.css"; ?></style>
<input type="hidden" id="csrf-token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, "UTF-8"); ?>">
<div class="adminHeaderContainer">
    <a class="adminDashboardLink" href="index.php">ADMIN PANEL</a>
    <a class="userHomeLink" href="../index.php">User Dashboard</a>
</div>
