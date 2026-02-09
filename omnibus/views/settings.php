<?php
global $pdo; 

if (!$pdo) {
    die("Database connection is not initialized.");
}

// --- START OF SAVING LOGIC ---
// Fixed typo: Changed REQUEST_CODE to REQUEST_METHOD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $new_avatar = $_POST['avatar_url'] ?? '';
    $new_bio = $_POST['bio'] ?? '';
    $user_id = $_SESSION['user_id'];

    // Update the database
    $update_stmt = $pdo->prepare("UPDATE users SET avatar_url = ?, bio = ? WHERE id = ?");
    
    if ($update_stmt->execute([$new_avatar, $new_bio, $user_id])) {
        $success_msg = "Profile updated successfully!";
    } else {
        $error_msg = "Something went wrong while saving.";
    }
}
// --- END OF SAVING LOGIC ---

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<div style="max-width: 600px; margin: 4rem auto;">
    <h1 class="text-gradient mb-4" style="font-family: 'Playfair Display', serif; font-size: 2.5rem;">Account Settings</h1>
    
    <?php if (isset($success_msg)): ?>
        <div style="background: rgba(74, 222, 128, 0.1); color: #4ade80; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(74, 222, 128, 0.2); text-align: center;">
            <?= $success_msg ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_msg)): ?>
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(239, 68, 68, 0.2); text-align: center;">
            <?= $error_msg ?>
        </div>
    <?php endif; ?>

    <form action="index.php?page=settings" method="POST" class="flex-col" style="
        background: rgba(255,255,255,0.03); 
        padding: 3rem; 
        border-radius: 30px; 
        border: 1px solid rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
    ">
        <input type="hidden" name="action" value="update_profile">
        
        <div style="margin-bottom: 2rem;">
            <label class="tag" style="display: block; margin-bottom: 0.5rem; color: #818cf8; letter-spacing: 1px; font-size: 0.8rem; text-transform: uppercase;">Profile Picture URL</label>
            <input type="text" name="avatar_url" 
                   value="<?= htmlspecialchars($user['avatar_url'] ?? '') ?>" 
                   placeholder="https://example.com/photo.jpg"
                   style="width: 100%; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); padding: 14px; border-radius: 12px; color: white; outline: none;">
        </div>
        
        <div style="margin-bottom: 2.5rem;">
            <label class="tag" style="display: block; margin-bottom: 0.5rem; color: #818cf8; letter-spacing: 1px; font-size: 0.8rem; text-transform: uppercase;">Bio</label>
            <textarea name="bio" rows="4" 
                      placeholder="Write something about yourself..."
                      style="width: 100%; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); padding: 14px; border-radius: 12px; color: white; resize: none; outline: none; line-height: 1.6;"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        </div>
        
        <button type="submit" class="btn-premium" style="width: 100%; justify-content: center; cursor: pointer; border: none; font-size: 1rem; padding: 14px;">
            Save Changes
        </button>
        
        <a href="index.php?page=profile" style="display: block; text-align: center; margin-top: 1.5rem; color: rgba(255,255,255,0.4); text-decoration: none; font-size: 0.9rem;">
            Back to Profile
        </a>
    </form>
</div>