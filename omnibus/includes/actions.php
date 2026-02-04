<?php
/**
 * OMNIBUS CORE ACTIONS
 * Handles all POST requests for Auth, Library, and Profile updates.
 */

// 1. Handle Registration in includes/actions.php
if ($page === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (register_user($pdo, $_POST['username'], $_POST['email'], $_POST['password'])) {
            header("Location: index.php?page=login&registered=success");
            exit;
        }
    } catch (PDOException $e) {
        // If the email already exists (Error 23000)
        if ($e->getCode() == 23000) {
            $error = "That email is already registered. Try logging in!";
        } else {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// 2. USER LOGIN
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (login_user($pdo, $email, $password)) {
        header("Location: index.php?page=dashboard");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}

// 3. USER LOGOUT
if ($page === 'logout') {
    session_destroy();
    header("Location: index.php?page=home");
    exit;
}

// 7. Handle Media Reviews
if (isset($_POST['action']) && $_POST['action'] === 'add_review') {
    if (!isset($_SESSION['user_id'])) exit;

    $media_id = (int)$_POST['media_id'];
    $content = htmlspecialchars(trim($_POST['review_content']));

    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, media_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $media_id, $content]);
        
        // Log this to activity feed too!
        $log_stmt = $pdo->prepare("INSERT INTO activity_log (user_id, media_id, action_type) VALUES (?, ?, 'wrote a review')");
        $log_stmt->execute([$_SESSION['user_id'], $media_id]);
    }

    header("Location: index.php?page=media_detail&id=" . $media_id . "&reviewed=1");
    exit;
}

// 4. LIBRARY UPDATES (Tracking & Ratings)
if (isset($_POST['action']) && $_POST['action'] === 'update_library') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $media_id = (int)$_POST['media_id'];
    $status = $_POST['status'];
    // Ensure rating is stored as an integer (1-5) or NULL
    $rating = (!empty($_POST['rating'])) ? (int)$_POST['rating'] : null;

    if (empty($status)) {
        // If "Not Tracked" is selected, remove the entry
        $stmt = $pdo->prepare("DELETE FROM user_library WHERE user_id = ? AND media_id = ?");
        $stmt->execute([$user_id, $media_id]);
    } else {
        // UPSERT Logic: Insert new record or update existing status/rating
        $stmt = $pdo->prepare("
            INSERT INTO user_library (user_id, media_id, status, rating, last_updated) 
            VALUES (?, ?, ?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE 
                status = VALUES(status), 
                rating = VALUES(rating),
                last_updated = NOW()
        ");
        $stmt->execute([$user_id, $media_id, $status, $rating]);
        
        // Log to Activity Feed
        $log_stmt = $pdo->prepare("
            INSERT INTO activity_log (user_id, media_id, action_type, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $log_stmt->execute([$user_id, $media_id, $status]);
    }

    header("Location: index.php?page=media_detail&id=" . $media_id . "&success=1");
    exit;
}

// 5. PROFILE & SETTINGS UPDATES
if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    if (!isset($_SESSION['user_id'])) exit;

    $bio = htmlspecialchars($_POST['bio']);
    $avatar = $_POST['avatar_url'];
    
    $stmt = $pdo->prepare("UPDATE users SET bio = ?, avatar_url = ? WHERE id = ?");
    $stmt->execute([$bio, $avatar, $_SESSION['user_id']]);
    
    header("Location: index.php?page=profile&updated=1");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'edit_media') {
    $media_id = (int)$_POST['media_id'];
    $new_cover = $_POST['cover_image'];
    
    $stmt = $pdo->prepare("UPDATE media SET cover_image = ? WHERE id = ?");
    $stmt->execute([$new_cover, $media_id]);
    
    header("Location: index.php?page=media_detail&id=" . $media_id);
    exit;
}

// 6. COMMUNITY MEDIA CONTRIBUTION (User Add)
if (isset($_POST['action']) && $_POST['action'] === 'user_add_media') {
    if (!isset($_SESSION['user_id'])) exit;

    $type = $_POST['type'];
    $title = htmlspecialchars($_POST['title']);
    $creator = htmlspecialchars($_POST['creator']);
    $year = (int)$_POST['release_year'];
    $image = $_POST['cover_image'];
    $desc = htmlspecialchars($_POST['description']);

    // Check for duplicates before inserting
    $check = $pdo->prepare("SELECT id FROM media WHERE title = ? AND type = ?");
    $check->execute([$title, $type]);
    $existing = $check->fetch();

    if ($existing) {
        header("Location: index.php?page=media_detail&id=" . $existing['id'] . "&exists=1");
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO media (type, title, creator, release_year, cover_image, description) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$type, $title, $creator, $year, $image, $desc]);
        
        $new_id = $pdo->lastInsertId();
        header("Location: index.php?page=media_detail&id=" . $new_id . "&new_entry=1");
    }
    exit;

    // 8. Admin Delete Media
// includes/actions.php

if (isset($_POST['action']) && $_POST['action'] === 'delete_media') {
    // 1. Verify Admin status
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
        $media_id = (int)$_POST['media_id'];

        try {
            $stmt = $pdo->prepare("DELETE FROM media WHERE id = ?");
            $stmt->execute([$media_id]);
            
            // Redirect to browse page after successful delete
            header("Location: index.php?page=browse&deleted=1");
            exit;
        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    } else {
        die("Access Denied: You do not have admin privileges.");
    }
}
}