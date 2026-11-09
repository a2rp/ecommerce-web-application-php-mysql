<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . "/../config/connect.php";
require_once __DIR__ . "/myFunctions.php";
require_once __DIR__ . "/csrf.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}
if (!verifyCsrfToken()) {
    http_response_code(403);
    exit("Invalid request token.");
}

if (isset($_POST["registerButton"])) {
    $name = trim((string) ($_POST["name"] ?? ""));
    $email = trim((string) ($_POST["email"] ?? ""));
    $phone = trim((string) ($_POST["phone"] ?? ""));
    $password = (string) ($_POST["password"] ?? "");
    $confirmPassword = (string) ($_POST["confirmPassword"] ?? "");

    if (
        $name === "" || strlen($name) > 120 ||
        filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($email) > 254 ||
        preg_match("/^[0-9+() .-]{7,24}$/", $phone) !== 1 ||
        $password === "" || strlen($password) > 72
    ) {
        redirect("Enter valid registration details.", "../register.php");
    }

    if ($password !== $confirmPassword) {
        redirect("Passwords do not match.", "../register.php");
    }

    $emailStatement = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $emailStatement->bind_param("s", $email);
    $emailStatement->execute();
    $emailStatement->store_result();
    $emailExists = $emailStatement->num_rows > 0;
    $emailStatement->close();

    if ($emailExists) {
        redirect("Email already registered.", "../register.php");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insertStatement = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $insertStatement->bind_param("ssss", $name, $email, $phone, $hashedPassword);
    if ($insertStatement->execute()) {
        redirect("Registered successfully.", "../login.php");
    }
    redirect("Something went wrong. Please try again.", "../register.php");
}

if (isset($_POST["loginButton"])) {
    $email = trim((string) ($_POST["email"] ?? ""));
    $password = (string) ($_POST["password"] ?? "");
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || $password === "") {
        redirect("Invalid email or password.", "../login.php");
    }

    $loginStatement = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
    $loginStatement->bind_param("s", $email);
    $loginStatement->execute();
    $user = $loginStatement->get_result()->fetch_assoc();
    $loginStatement->close();

    if (!$user || !password_verify($password, $user["password"])) {
        redirect("Invalid email or password.", "../login.php");
    }

    session_regenerate_id(true);
    $_SESSION["auth"] = true;
    $_SESSION["authUser"] = [
        "userId" => (int) $user["id"],
        "name" => $user["name"],
        "email" => $user["email"],
    ];
    $_SESSION["role"] = (int) $user["role"];

    if ((int) $user["role"] === 1) {
        redirect("Welcome to Dashboard.", "../admin/index.php");
    }
    redirect("Login successful.", "../index.php");
}

http_response_code(400);
exit("Invalid request.");
?>
