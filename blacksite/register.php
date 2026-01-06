<?php
include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["username"];
    $email = $_POST["email"];
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $conn->query("INSERT INTO users (username,email,password) VALUES ('$user','$email','$pass')");
    header("Location: login.php");
}
?>

<form method="POST" class="auth-form">
    <h2>Create Account</h2>
    <input name="username" placeholder="Username" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button>Register</button>
</form>
