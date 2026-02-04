<?php
// includes/auth.php

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function login_user($pdo, $email, $password) {
    $stmt = $pdo->prepare("SELECT id, username, email, password_hash, is_admin, avatar_url FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = (int)$user['is_admin']; 
        $_SESSION['avatar_url'] = $user['avatar_url'];
        return true;
    }
    return false;
}

function register_user($pdo, $username, $email, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hash]);
}
?>