<?php
include "config.php";
include "db.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: dashboard.php");
        } else {
            echo "Wrong password";
        }
    } else {
        echo "User not found";
    }
}
?>

<link rel="stylesheet" href="style.css">

<form method="POST">
    <h2>Login</h2>
    <input type="text" name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button name="login">Login</button>
</form>
