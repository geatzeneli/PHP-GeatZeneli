<?php
/**
 * OMNIBUS CORE ACTIONS
 * Handles all POST requests for Auth, Library, and Profile updates.
 */

// 1. Handle Registration
if ($page === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (register_user($pdo, $_POST['username'], $_POST['email'], $_POST['password'])) {
            header("Location: index.php?page=login&registered=success");
            exit;
        }
    } catch (PDOException $e) {
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

// 4. COMMUNITY EDIT: EDIT TITLE (Wiki-style, any logged-in user)
if (isset($_POST['action']) && $_POST['action'] === 'edit_title') {
    if (!isset($_SESSION['user_id'])) exit;

    $media_id = (int)$_POST['media_id'];
    $new_title = htmlspecialchars(trim($_POST['new_title']));

    if (!empty($new_title)) {
        $stmt = $pdo->prepare("UPDATE media SET title = ? WHERE id = ?");
        $stmt->execute([$new_title, $media_id]);
    }
    
    header("Location: index.php?page=media_detail&id=" . $media_id . "&updated=title");
    exit;
}

// 5. COMMUNITY EDIT: EDIT POSTER (Wiki-style, any logged-in user)
if (isset($_POST['action']) && $_POST['action'] === 'edit_media') {
    if (!isset($_SESSION['user_id'])) exit;

    $media_id = (int)$_POST['media_id'];
    $new_cover = $_POST['cover_image'];
    
    $stmt = $pdo->prepare("UPDATE media SET cover_image = ? WHERE id = ?");
    $stmt->execute([$new_cover, $media_id]);
    
    header("Location: index.php?page=media_detail&id=" . $media_id . "&updated=poster");
    exit;
}

// 6. LIBRARY UPDATES (Tracking, Ratings, & Favorites)
if (isset($_POST['action']) && $_POST['action'] === 'update_library') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?page=login");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $media_id = (int)$_POST['media_id'];
    $status = $_POST['status'];
    $rating = (!empty($_POST['rating'])) ? (int)$_POST['rating'] : null;
    $is_favorite = isset($_POST['is_favorite']) ? (int)$_POST['is_favorite'] : 0;

    if (empty($status)) {
        $stmt = $pdo->prepare("DELETE FROM user_library WHERE user_id = ? AND media_id = ?");
        $stmt->execute([$user_id, $media_id]);
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO user_library (user_id, media_id, status, rating, is_favorite, last_updated) 
            VALUES (?, ?, ?, ?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE 
                status = VALUES(status), 
                rating = VALUES(rating),
                is_favorite = VALUES(is_favorite),
                last_updated = NOW()
        ");
        $stmt->execute([$user_id, $media_id, $status, $rating, $is_favorite]);
        
        $log_stmt = $pdo->prepare("INSERT INTO activity_log (user_id, media_id, action_type, created_at) VALUES (?, ?, ?, NOW())");
        $log_stmt->execute([$user_id, $media_id, $status]);
    }

    header("Location: index.php?page=media_detail&id=" . $media_id . "&success=1");
    exit;
}

// 7. REVIEWS
if (isset($_POST['action']) && $_POST['action'] === 'add_review') {
    if (!isset($_SESSION['user_id'])) exit;

    $media_id = (int)$_POST['media_id'];
    $content = htmlspecialchars(trim($_POST['review_content']));

    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, media_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $media_id, $content]);
    }

    header("Location: index.php?page=media_detail&id=" . $media_id . "&reviewed=1");
    exit;
}

// 8. ADD NEW MEDIA (Community contribution)
if (isset($_POST['action']) && $_POST['action'] === 'user_add_media') {
    if (!isset($_SESSION['user_id'])) exit;

    $type = $_POST['type'];
    $title = htmlspecialchars($_POST['title']);
    $creator = htmlspecialchars($_POST['creator']);
    $year = (int)$_POST['release_year'];
    $image = $_POST['cover_image'];
    $desc = htmlspecialchars($_POST['description']);

    $check = $pdo->prepare("SELECT id FROM media WHERE title = ? AND type = ?");
    $check->execute([$title, $type]);
    $existing = $check->fetch();

    if ($existing) {
        header("Location: index.php?page=media_detail&id=" . $existing['id'] . "&exists=1");
    } else {
        $stmt = $pdo->prepare("INSERT INTO media (type, title, creator, release_year, cover_image, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$type, $title, $creator, $year, $image, $desc]);
        header("Location: index.php?page=media_detail&id=" . $pdo->lastInsertId() . "&new_entry=1");
    }
    exit;
}

// 9. ADMIN DELETE MEDIA (Strictly Admin)
if (isset($_POST['action']) && $_POST['action'] === 'delete_media') {
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
        $media_id = (int)$_POST['media_id'];
        
        // Delete related library entries and reviews first to avoid foreign key issues
        $pdo->prepare("DELETE FROM user_library WHERE media_id = ?")->execute([$media_id]);
        $pdo->prepare("DELETE FROM reviews WHERE media_id = ?")->execute([$media_id]);
        
        $stmt = $pdo->prepare("DELETE FROM media WHERE id = ?");
        $stmt->execute([$media_id]);
        
        header("Location: index.php?page=browse&deleted=1");
        exit;
    } else {
        die("Access Denied.");
    }


}

// 10. UPDATE PROFILE (Bio & Avatar ONLY)
if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    if (!isset($_SESSION['user_id'])) exit;

    $user_id = $_SESSION['user_id'];
    $new_bio = htmlspecialchars(trim($_POST['bio']));
    $new_avatar = trim($_POST['avatar_url']);

    // Update the database
    $stmt = $pdo->prepare("UPDATE users SET bio = ?, avatar_url = ? WHERE id = ?");
    $stmt->execute([$new_bio, $new_avatar, $user_id]);

    // Redirect back to profile
    header("Location: index.php?page=profile&updated=1");
    exit;
}