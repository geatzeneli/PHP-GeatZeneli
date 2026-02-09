<?php
// Tell the file to look for the database connection defined in index.php
global $pdo; 

if (!$pdo) {
    // This will help us debug if the connection is actually missing
    die("Database connection ($pdo) is not initialized. Check your index.php order.");
}

// Now fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<div style="max-width: 600px; margin: 0 auto;">
    <h1 class="text-gradient mb-4">Account Settings</h1>
    
    <form action="index.php?page=settings" method="POST" class="flex-col" style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
        <input type="hidden" name="action" value="update_profile">
        
        <label class="tag">Profile Picture URL</label>
        <input type="text" name="avatar_url" value="<?= htmlspecialchars($user['avatar_url'] ?? '') ?>" placeholder="https://example.com/my-photo.jpg">
        
        <label class="tag" style="margin-top: 1.5rem;">Bio</label>
        <textarea name="bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
        
        <button type="submit" class="btn btn-primary" style="margin-top: 2rem;">Save Changes</button>
    </form>
</div>