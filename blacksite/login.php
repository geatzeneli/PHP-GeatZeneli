<?php
include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $res = $conn->query("SELECT * FROM users WHERE email='{$_POST["email"]}'");
    $user = $res->fetch_assoc();

    if ($user && password_verify($_POST["password"], $user["password"])) {
        $_SESSION["user"] = $user["username"];
        header("Location: dashboard.php");
    }
}
?>

<form method="POST" class="auth-form">
    <h2>Login</h2>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button>Login</button>
</form>
