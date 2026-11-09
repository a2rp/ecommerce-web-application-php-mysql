<?php
function csrfToken() {
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function csrfInput() {
    $token = htmlspecialchars(csrfToken(), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function verifyCsrfToken() {
    $expected = $_SESSION["csrf_token"] ?? "";
    $provided = $_POST["csrf_token"] ?? $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
    return is_string($expected) && $expected !== "" && is_string($provided) && hash_equals($expected, $provided);
}
?>
