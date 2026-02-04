<?php
// Determine whose profile we are viewing
$profile_id = $_GET['id'] ?? $_SESSION['user_id'] ?? null;

if (!$profile_id) {
    header("Location: index.php?page=login");
    exit;
}

// 1. Fetch User Info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$profile_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<div class='app-container'><h2>User not found.</h2></div>";
    exit;
}

// 2. Fetch 'Completed' items for the Finished Shelf
$stmt = $pdo->prepare("
    SELECT m.*, ul.rating, ul.status 
    FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND ul.status = 'completed' 
    ORDER BY ul.last_updated DESC
");
$stmt->execute([$profile_id]);
$finished_items = $stmt->fetchAll();

$is_own_profile = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile_id);
?>

<div class="profile-container">
    <header class="profile-header mb-5" style="text-align: center; padding: 4rem 2rem; background: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
        <img src="<?= $user['avatar_url'] ?: 'https://ui-avatars.com/api/?name='.urlencode($user['username']).'&background=random' ?>" 
             style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid var(--primary); box-shadow: var(--shadow-glow); margin-bottom: 1.5rem;">
        
        <h1 class="text-gradient" style="font-size: 3rem;"><?= htmlspecialchars($user['username']) ?></h1>
        <p style="color: var(--text-muted); max-width: 600px; margin: 1rem auto; line-height: 1.6;">
            <?= htmlspecialchars($user['bio'] ?: 'This curator has not written a bio yet.') ?>
        </p>

        <?php if ($is_own_profile): ?>
            <a href="index.php?page=settings" class="btn btn-ghost" style="margin-top: 1rem;">Edit Profile</a>
        <?php endif; ?>
    </header>

    <section>
        <div class="flex-row mb-4" style="justify-content: space-between; align-items: center; border-left: 4px solid var(--primary); padding-left: 1rem;">
            <h2 style="font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 2px;">The Finished Shelf</h2>
            <span class="tag"><?= count($finished_items) ?> Items</span>
        </div>

        <div class="grid-media">
            <?php if (empty($finished_items)): ?>
                <div style="grid-column: 1/-1; padding: 5rem; text-align: center; background: var(--glass); border-radius: var(--radius-md); border: 1px dashed var(--border-subtle);">
                    <p style="color: var(--text-muted);">No masterpieces logged in the "Completed" section yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($finished_items as $item): ?>
                    <div class="media-card">
                        <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                            <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster" alt="Cover">
                            <div class="media-info">
                                <h4 style="font-size: 0.95rem; margin-bottom: 0.4rem;"><?= htmlspecialchars($item['title']) ?></h4>
                                <div style="color: #FFD700; font-size: 0.8rem;">
                                    <?php 
                                        if ($item['rating']) {
                                            echo str_repeat('★', $item['rating']) . str_repeat('☆', 5 - $item['rating']);
                                        } else {
                                            echo "<span style='color: var(--text-muted); opacity: 0.5;'>Unrated</span>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>