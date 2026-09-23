<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<style><?php include "userAssets/footer.css"; ?></style>
<footer class="footerContainer">
    <div class="footerNav">
        <strong>Ecommerce Application</strong>
        <a class="footerNavLink" href="index.php">Home</a>
        <?php if (isset($_SESSION["authUser"])) { ?>
            <a class="footerNavLink" href="cart.php">Cart</a>
            <a class="footerNavLink" href="logout.php">Logout</a>
        <?php } else { ?>
            <a class="footerNavLink" href="login.php">Login</a>
            <a class="footerNavLink" href="register.php">Register</a>
        <?php } ?>
    </div>
    <address class="footerAddress">
        <strong>Visit</strong>
        <span>Krishna Raj Puram, Seegahalli, Karnataka, India</span>
        <a href="mailto:ash.ranjan09@gmail.com">ash.ranjan09@gmail.com</a>
    </address>
    <iframe class="footerMap" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d497700.1123431985!2d77.3012611768966!3d12.953790192004355!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae1670c9b44e6d%3A0xf8dfc3e8517e4fe0!2sBengaluru%2C%20Karnataka!5e0!3m2!1sen!2sin!4v1690837412575!5m2!1sen!2sin" title="Bengaluru map" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <div class="footerBottom">
        <p>Copyright &copy; <?php echo date("Y"); ?> <a href="https://www.ashishranjan.net" target="_blank" rel="noopener noreferrer">Ashish Ranjan</a></p>
        <nav class="footerSocialLinks" aria-label="Social and support links">
            <a href="https://www.ashishranjan.net/" target="_blank" rel="noopener noreferrer" aria-label="Portfolio" title="Portfolio"><i class="fa-solid fa-globe" aria-hidden="true"></i></a>
            <a href="https://github.com/a2rp" target="_blank" rel="noopener noreferrer" aria-label="GitHub" title="GitHub"><i class="fa-brands fa-github" aria-hidden="true"></i></a>
            <a href="https://codepen.io/ash1198" target="_blank" rel="noopener noreferrer" aria-label="CodePen" title="CodePen"><i class="fa-brands fa-codepen" aria-hidden="true"></i></a>
            <a href="https://www.linkedin.com/in/aashishranjan" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" title="LinkedIn"><i class="fa-brands fa-linkedin" aria-hidden="true"></i></a>
            <a href="https://www.facebook.com/theash.ashish/" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook"><i class="fa-brands fa-facebook" aria-hidden="true"></i></a>
            <a href="https://www.youtube.com/@ashishranjan-ashz?sub_confirmation=1" target="_blank" rel="noopener noreferrer" aria-label="YouTube" title="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
            <a href="mailto:ash.ranjan09@gmail.com" aria-label="Email" title="Email"><i class="fa-solid fa-envelope" aria-hidden="true"></i></a>
            <a href="https://a2rp-donation-page.netlify.app/" target="_blank" rel="noopener noreferrer" aria-label="Support" title="Support"><i class="fa-solid fa-heart" aria-hidden="true"></i></a>
            <a href="https://buymeacoffee.com/a2rp" target="_blank" rel="noopener noreferrer" aria-label="Buy Me a Coffee" title="Buy Me a Coffee"><i class="fa-solid fa-mug-hot" aria-hidden="true"></i></a>
            <a href="https://www.patreon.com/a2rp" target="_blank" rel="noopener noreferrer" aria-label="Patreon" title="Patreon"><i class="fa-brands fa-patreon" aria-hidden="true"></i></a>
        </nav>
    </div>
</footer>
<button class="goToTop" type="button" aria-label="Scroll to top" title="Scroll to top"><i class="fa-solid fa-arrow-up" aria-hidden="true"></i></button>
<script>
    const goToTop = document.querySelector(".goToTop");
    window.addEventListener("scroll", () => {
        goToTop?.classList.toggle("isVisible", window.scrollY > 280);
    }, { passive: true });
    goToTop?.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
</script>
</body>
</html>
