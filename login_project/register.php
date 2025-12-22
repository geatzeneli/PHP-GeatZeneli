<?php
include "config.php";
include "db.php";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "Registered successfully. <a href='login.php'>Login</a>";
    } else {
        echo "Error: Username already exists.";
    }
}
?>

<link rel="stylesheet" href="style.css">

<form method="POST">
    <h2>Register</h2>
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <button name="register">Register</button>
</form>
