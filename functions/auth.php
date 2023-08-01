<?php
session_start();
include "../config/connect.php";
include "myFunctions.php";

if (isset($_POST['registerButton'])) {
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn,$_POST['confirmPassword']);

    // checking if email already registered
    $emailQuery = "SELECT email FROM users WHERE email = '$email'";
    $emailRun = mysqli_query($conn, $emailQuery);
    if (mysqli_num_rows($emailRun)>0) {
        $_SESSION["message"] = "Email already registered";
        header("Location: ../register.php");
    } else {
        if ($password == $confirmPassword) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $insertQuery = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";
            $result = mysqli_query($conn, $insertQuery);
            if ($result) {
                redirect("Registered successfully", "../login.php");
            } else {
                redirect("Something went wrong.", "../register.php");
            }
        } else {
            redirect("Passwords do not match.", "../register.php");
        }
    }
} else if (isset($_POST['loginButton'])) {
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $loginQuery = "SELECT * FROM users WHERE email = '$email'";
    $loginRun = mysqli_query($conn,$loginQuery);
    if (mysqli_num_rows($loginRun)==0) {
        redirect("Email not registered.", "../login.php");
    } else {
        while ($row = mysqli_fetch_assoc($loginRun)) {
            if(password_verify($password, $row["password"])) {
                $_SESSION["auth"] = true;
                $userId = $row["id"];
                $name = $row["name"];
                $email = $row["email"];
                $role = $row["role"];
                $_SESSION["authUser"] = [
                    "userId" => $userId,
                    "name" => $name,
                    "email" => $email
                ];
                $_SESSION["role"] = $role;
                if($role==1) {
                    redirect("Welcome to Dashboard.", "../admin/index.php");
                } else {
                    redirect("Login successful.", "../index.php");
                }
            } else {
                redirect("Password Incorrect.", "../login.php");
            }
        }
    }
}
?>
