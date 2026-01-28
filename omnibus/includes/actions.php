<?php
// includes/actions.php

// 1. Handle Registration
if ($page === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (register_user($pdo, $username, $email, $password)) {
        header("Location: index.php?page=login&registered=success");
        exit;
    } else {
        $error = "Registration failed. Username or Email might be taken.";
    }
}

// 2. Handle Login
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (login_user($pdo, $email, $password)) {
        header("Location: index.php?page=dashboard");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}

// 3. Handle Logout
if ($page === 'logout') {
    session_destroy();
    header("Location: index.php?page=home");
    exit;
}

// 4. Update User Library (Tracking Status & Rating)
if (isset($_POST['action']) && $_POST['action'] === 'update_library') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $media_id = (int)$_POST['media_id'];
    $status = $_POST['status'];
    $rating = !empty($_POST['rating']) ? (int)$_POST['rating'] : null;

    if (empty($status)) {
        $stmt = $pdo->prepare("DELETE FROM user_library WHERE user_id = ? AND media_id = ?");
        $stmt->execute([$user_id, $media_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO user_library (user_id, media_id, status, rating) 
                               VALUES (?, ?, ?, ?) 
                               ON DUPLICATE KEY UPDATE status = VALUES(status), rating = VALUES(rating)");
        $stmt->execute([$user_id, $media_id, $status, $rating]);
        
        // Log to Activity Feed
        $log_stmt = $pdo->prepare("INSERT INTO activity_log (user_id, media_id, action_type) VALUES (?, ?, ?)");
        $log_stmt->execute([$user_id, $media_id, $status]);
    }

    header("Location: index.php?page=media_detail&id=" . $media_id . "&success=1");
    exit;
}

// 5. Update Profile (Bio & Avatar)
if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $bio = htmlspecialchars($_POST['bio']);
    $avatar = $_POST['avatar_url'];
    
    $stmt = $pdo->prepare("UPDATE users SET bio = ?, avatar_url = ? WHERE id = ?");
    $stmt->execute([$bio, $avatar, $_SESSION['user_id']]);
    
    header("Location: index.php?page=profile&updated=1");
    exit;
}

// 6. User-Driven Media Addition (The Community Contribution)
if (isset($_POST['action']) && $_POST['action'] === 'user_add_media') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit;
    }

    $type = $_POST['type'];
    $title = htmlspecialchars($_POST['title']);
    $creator = htmlspecialchars($_POST['creator']);
    $year = (int)$_POST['release_year'];
    $image = $_POST['cover_image'];
    $desc = htmlspecialchars($_POST['description']);

    // Prevent duplicates (Check if Title + Type exists)
    $check = $pdo->prepare("SELECT id FROM media WHERE title = ? AND type = ?");
    $check->execute([$title, $type]);
    $existing = $check->fetch();

    if ($existing) {
        // If it already exists, don't create a new one, just go to the existing page
        header("Location: index.php?page=media_detail&id=" . $existing['id'] . "&exists=1");
    } else {
        $stmt = $pdo->prepare("INSERT INTO media (type, title, creator, release_year, cover_image, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$type, $title, $creator, $year, $image, $desc]);
        
        $new_id = $pdo->lastInsertId();
        header("Location: index.php?page=media_detail&id=" . $new_id . "&new_entry=1");
    }
    exit;
}