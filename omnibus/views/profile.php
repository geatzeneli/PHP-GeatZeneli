<?php
// 1. Determine whose profile we are viewing
$profile_id = $_GET['id'] ?? $_SESSION['user_id'] ?? null;

if (!$profile_id) {
    header("Location: index.php?page=login");
    exit;
}

// 2. FETCH User Info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$profile_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<div class='app-container'><h2>User not found.</h2></div>";
    exit;
}

// 3. FETCH Top 5 Favorites
$stmt = $pdo->prepare("
    SELECT m.* FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND ul.is_favorite = 1 
    LIMIT 5
");
$stmt->execute([$profile_id]);
$favorite_items = $stmt->fetchAll();

// 4. FETCH 'Completed' items
$stmt = $pdo->prepare("
    SELECT m.*, ul.rating, ul.status 
    FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND ul.status = 'completed' 
    ORDER BY ul.last_updated DESC
");
$stmt->execute([$profile_id]);
$finished_items = $stmt->fetchAll();

// 5. FETCH Watchlist (Planned + In Progress)
$stmt = $pdo->prepare("
    SELECT m.*, ul.status 
    FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND (ul.status = 'want' OR ul.status = 'consuming') 
    ORDER BY ul.last_updated DESC
");
$stmt->execute([$profile_id]);
$watchlist_items = $stmt->fetchAll();

$is_own_profile = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile_id);
?>

<div class="profile-container" style="padding: 2rem 0;">
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

    <?php if (!empty($favorite_items)): ?>
    <section class="mb-5">
        <div class="flex-row mb-4" style="border-left: 4px solid #FFD700; padding-left: 1rem;">
            <h2 style="font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 2px; color: #FFD700;">Curator's Top 5</h2>
        </div>
        <div class="grid-media" style="grid-template-columns: repeat(5, 1fr); display: grid; gap: 1.5rem;">
            <?php foreach ($favorite_items as $fav): ?>
                <div class="media-card" style="border: 1px solid #FFD700; box-shadow: 0 0 10px rgba(255, 215, 0, 0.1);">
                    <a href="index.php?page=media_detail&id=<?= $fav['id'] ?>">
                        <img src="<?= htmlspecialchars($fav['cover_image']) ?>" class="media-poster" style="width: 100%; border-radius: var(--radius-sm);">
                        <div class="media-info" style="padding: 10px;">
                            <h4 style="font-size: 0.8rem; color: #FFD700; margin: 0;"><?= htmlspecialchars($fav['title']) ?></h4>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="mb-5">
        <div class="flex-row mb-4" style="justify-content: space-between; align-items: center; border-left: 4px solid var(--primary); padding-left: 1rem; display: flex;">
            <h2 style="font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 2px;">The Finished Shelf</h2>
            <span class="tag" style="background: var(--primary); color: black; padding: 4px 12px; border-radius: 20px; font-weight: bold;"><?= count($finished_items) ?> Items</span>
        </div>
        <div class="grid-media" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem;">
            <?php if (empty($finished_items)): ?>
                <div style="grid-column: 1/-1; padding: 5rem; text-align: center; background: var(--glass); border-radius: var(--radius-md); border: 1px dashed var(--border-subtle);">
                    <p style="color: var(--text-muted);">No masterpieces logged in the "Completed" section yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($finished_items as $item): ?>
                    <div class="media-card">
                        <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                            <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster" alt="Cover" style="width: 100%; border-radius: var(--radius-sm);">
                            <div class="media-info" style="padding: 10px;">
                                <h4 style="font-size: 0.95rem; margin-bottom: 0.4rem;"><?= htmlspecialchars($item['title']) ?></h4>
                                <div style="color: #FFD700; font-size: 0.8rem;">
                                    <?= $item['rating'] ? str_repeat('★', $item['rating']) . str_repeat('☆', 5 - $item['rating']) : "Unrated" ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="mb-5">
        <div class="flex-row mb-4" style="justify-content: space-between; align-items: center; border-left: 4px solid #00d4ff; padding-left: 1rem; display: flex;">
            <h2 style="font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 2px; color: #00d4ff;">Watchlist / Planned</h2>
            <span class="tag" style="border: 1px solid #00d4ff; color: #00d4ff; padding: 4px 12px; border-radius: 20px;"><?= count($watchlist_items) ?> Items</span>
        </div>

        <div class="grid-media" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem;">
            <?php if (empty($watchlist_items)): ?>
                <div style="grid-column: 1/-1; padding: 5rem; text-align: center; background: var(--glass); border-radius: var(--radius-md); border: 1px dashed var(--border-subtle);">
                    <p style="color: var(--text-muted);">Nothing planned yet. Start exploring to fill your watchlist!</p>
                </div>
            <?php else: ?>
                <?php foreach ($watchlist_items as $watch): ?>
                    <div class="media-card" style="border-bottom: 2px solid #00d4ff;">
                        <a href="index.php?page=media_detail&id=<?= $watch['id'] ?>">
                            <img src="<?= htmlspecialchars($watch['cover_image']) ?>" class="media-poster" alt="Cover" style="width: 100%; border-radius: var(--radius-sm);">
                            <div class="media-info" style="padding: 10px;">
                                <h4 style="font-size: 0.95rem; margin-bottom: 0.4rem;"><?= htmlspecialchars($watch['title']) ?></h4>
                                <span style="font-size: 0.7rem; color: #00d4ff; text-transform: uppercase; font-weight: bold;">
                                    <?= $watch['status'] == 'consuming' ? 'In Progress' : 'Planned' ?>
                                </span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>