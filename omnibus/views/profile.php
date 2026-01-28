<?php
// Determine which user to show
$profile_id = $_GET['id'] ?? $_SESSION['user_id'] ?? null;

if (!$profile_id) {
    header("Location: index.php?page=login");
    exit;
}

// 1. Fetch User Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$profile_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

// 2. Fetch Completed Items
// We use a JOIN to get the media details (title, image) from the library link
$stmt = $pdo->prepare("
    SELECT m.*, ul.rating, ul.status 
    FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND ul.status = 'completed'
    ORDER BY m.title ASC
");
$stmt->execute([$profile_id]);
$completed_items = $stmt->fetchAll();

$is_own_profile = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile_id);
?>

<div class="profile-container" style="max-width: 1000px; margin: 0 auto;">
    
    <div class="profile-header" style="text-align: center; padding: 3rem; background: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border-subtle); margin-bottom: 3rem;">
        <img src="<?= !empty($user['avatar_url']) ? htmlspecialchars($user['avatar_url']) : 'https://ui-avatars.com/api/?name='.urlencode($user['username']).'&background=random' ?>" 
             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary); margin-bottom: 1rem;">
        
        <h1 class="text-gradient" style="font-size: 2.5rem;"><?= htmlspecialchars($user['username']) ?></h1>
        <p style="color: var(--text-muted); margin: 1rem 0;"><?= htmlspecialchars($user['bio'] ?? 'No bio yet.') ?></p>
        
        <?php if ($is_own_profile): ?>
            <a href="index.php?page=settings" class="btn btn-ghost" style="font-size: 0.8rem;">Edit Profile & Photo</a>
        <?php endif; ?>
    </div>

    <h2 class="mb-4" style="font-family: var(--font-heading);">The Finished Shelf</h2>
    <div class="grid-media">
        <?php if (empty($completed_items)): ?>
            <p style="grid-column: 1/-1; color: var(--text-muted); text-align: center; padding: 3rem; background: var(--glass); border-radius: var(--radius-md);">
                Nothing here yet.
            </p>
        <?php else: ?>
            <?php foreach ($completed_items as $item): ?>
                <div class="media-card">
                    <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster">
                        <div class="media-info">
                            <span class="tag <?= $item['type'] ?>"><?= $item['type'] ?></span>
                            <h4 style="margin-top: 0.5rem;"><?= htmlspecialchars($item['title']) ?></h4>
                            <?php if($item['rating']): ?>
                                <div style="color: #FFD700; margin-top: 5px;">
                                    <?= str_repeat('★', $item['rating']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>