<?php
// 1. DATABASE CONNECTION & SCOPE FIX
global $pdo;

// 2. Determine whose profile we are viewing
$profile_id = $_GET['id'] ?? $_SESSION['user_id'] ?? null;

if (!$profile_id) {
    header("Location: index.php?page=login");
    exit;
}

// 3. FETCH User Info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$profile_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<div class='app-container'><h2>User not found.</h2></div>";
    exit;
}

// 4. FETCH Top 5 Favorites
$stmt = $pdo->prepare("
    SELECT m.* FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND ul.is_favorite = 1 
    LIMIT 5
");
$stmt->execute([$profile_id]);
$favorite_items = $stmt->fetchAll();

// 5. FETCH 'Currently Consuming' (In Progress)
$stmt = $pdo->prepare("
    SELECT m.*, ul.status 
    FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND ul.status = 'consuming' 
    ORDER BY ul.last_updated DESC
");
$stmt->execute([$profile_id]);
$in_progress_items = $stmt->fetchAll();

// 6. FETCH 'Completed' items
$stmt = $pdo->prepare("
    SELECT m.*, ul.rating, ul.status 
    FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND ul.status = 'completed' 
    ORDER BY ul.last_updated DESC
");
$stmt->execute([$profile_id]);
$finished_items = $stmt->fetchAll();

// 7. FETCH Watchlist (Planned ONLY)
$stmt = $pdo->prepare("
    SELECT m.*, ul.status 
    FROM user_library ul 
    JOIN media m ON ul.media_id = m.id 
    WHERE ul.user_id = ? AND ul.status = 'want' 
    ORDER BY ul.last_updated DESC
");
$stmt->execute([$profile_id]);
$watchlist_items = $stmt->fetchAll();

$is_own_profile = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile_id);
?>

<style>
    /* PREMIUM CINEMATIC CARD STYLES */
    .premium-profile-card {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08); 
        border-radius: 40px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .avatar-ring {
        position: relative;
        padding: 5px;
        background: linear-gradient(45deg, #6366f1, #a855f7); /* Purple/Blue Accent */
        border-radius: 50%;
        display: inline-block;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .avatar-ring:hover {
        transform: scale(1.05) rotate(2deg);
        box-shadow: 0 0 30px rgba(99, 102, 241, 0.4);
    }

    .stat-pill {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 10px 24px;
        border-radius: 100px;
        display: flex;
        flex-direction: column;
        min-width: 90px;
        transition: background 0.3s ease;
    }

    .stat-pill:hover { background: rgba(255, 255, 255, 0.06); }

    .btn-premium {
        background: linear-gradient(90deg, #6366f1, #a855f7);
        color: white !important;
        border: none;
        padding: 14px 36px;
        border-radius: 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
        filter: brightness(1.1);
    }

    /* SHELF STYLES */
    .media-card-hover {
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.3s ease;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.05);
        background: #000;
    }
    .media-card-hover:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.6), 0 0 20px rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.3);
    }
    .shelf-header {
        letter-spacing: 2px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
        opacity: 0.9;
    }
</style>

<div class="profile-container" style="padding: 4rem 1rem; max-width: 1200px; margin: 0 auto;">
    
    <header class="premium-profile-card" style="max-width: 800px; margin: 0 auto 6rem auto; text-align: center; padding: 5rem 2rem;">
        
        <div style="position: absolute; top: -100px; left: 50%; transform: translateX(-50%); width: 300px; height: 300px; background: rgba(99, 102, 241, 0.15); filter: blur(100px); pointer-events: none; z-index: 0;"></div>

        <div style="position: relative; z-index: 1;">
            <div class="avatar-ring" style="margin-bottom: 2.5rem;">
                <img src="<?= $user['avatar_url'] ?: 'https://ui-avatars.com/api/?name='.urlencode($user['username']).'&background=random' ?>" 
                     style="width: 140px; height: 140px; object-fit: cover; border-radius: 50%; border: 5px solid #0b0c10;">
            </div>
            
            <h1 class="text-gradient" style="font-size: 3.5rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -1.5px;">
                <?= htmlspecialchars($user['username']) ?>
            </h1>
            
            <div style="text-transform: uppercase; letter-spacing: 4px; font-size: 0.75rem; color: #818cf8; font-weight: 700; margin-bottom: 1.5rem; opacity: 0.8;">
                Elite Curator
            </div>

            <p style="color: rgba(255,255,255,0.6); max-width: 500px; margin: 0 auto 3rem auto; line-height: 1.7; font-size: 1.1rem; font-weight: 300;">
                "<?= htmlspecialchars($user['bio'] ?: 'This curator is currently building their digital archive.') ?>"
            </p>

            <div style="display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 3.5rem;">
                <div class="stat-pill">
                    <span style="font-size: 1.3rem; font-weight: 800; color: white;"><?= count($favorite_items) ?></span>
                    <span style="font-size: 0.6rem; text-transform: uppercase; color: rgba(255,255,255,0.4); letter-spacing: 1px;">Top Picks</span>
                </div>
                <div class="stat-pill">
                    <span style="font-size: 1.3rem; font-weight: 800; color: white;"><?= count($finished_items) ?></span>
                    <span style="font-size: 0.6rem; text-transform: uppercase; color: rgba(255,255,255,0.4); letter-spacing: 1px;">Finished</span>
                </div>
                <div class="stat-pill">
                    <span style="font-size: 1.3rem; font-weight: 800; color: white;"><?= count($watchlist_items) ?></span>
                    <span style="font-size: 0.6rem; text-transform: uppercase; color: rgba(255,255,255,0.4); letter-spacing: 1px;">Planned</span>
                </div>
            </div>

            <?php if ($is_own_profile): ?>
                <div>
                    <a href="index.php?page=settings" class="btn-premium">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Profile
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if (!empty($favorite_items)): ?>
    <section class="mb-5">
        <div class="shelf-header" style="color: #FFD700;">
            <div style="width: 40px; height: 1px; background: #FFD700;"></div> Curator's Top 5
        </div>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.5rem;">
            <?php foreach ($favorite_items as $fav): ?>
                <div class="media-card-hover" style="border-color: rgba(255, 215, 0, 0.2);">
                    <a href="index.php?page=media_detail&id=<?= $fav['id'] ?>">
                        <img src="<?= htmlspecialchars($fav['cover_image']) ?>" style="width: 100%; aspect-ratio: 2/3; object-fit: cover;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="mb-5">
        <div class="shelf-header" style="color: var(--primary);">
            <div style="width: 40px; height: 1px; background: var(--primary);"></div> Currently In Progress
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 2rem;">
            <?php if (empty($in_progress_items)): ?>
                <p style="color: var(--text-muted); font-size: 0.9rem;">No active logs in the archive.</p>
            <?php else: ?>
                <?php foreach ($in_progress_items as $item): ?>
                    <div class="media-card-hover" style="border-color: rgba(99, 102, 241, 0.3);">
                        <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                            <img src="<?= htmlspecialchars($item['cover_image']) ?>" style="width: 100%; aspect-ratio: 2/3; object-fit: cover;">
                            <div style="padding: 12px; background: rgba(0,0,0,0.4);">
                                <div style="height: 3px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                                    <div style="width: 65%; height: 100%; background: var(--primary); box-shadow: 0 0 10px var(--primary);"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="mb-5">
        <div class="shelf-header" style="color: #4ade80;">
            <div style="width: 40px; height: 1px; background: #4ade80;"></div> The Finished Shelf
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.5rem;">
            <?php foreach ($finished_items as $item): ?>
                <div class="media-card-hover">
                    <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['cover_image']) ?>" style="width: 100%; aspect-ratio: 2/3; object-fit: cover; opacity: 0.8;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="mb-5">
        <div class="shelf-header" style="color: #00d4ff;">
            <div style="width: 40px; height: 1px; background: #00d4ff;"></div> Watchlist Archive
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.5rem;">
            <?php foreach ($watchlist_items as $watch): ?>
                <div class="media-card-hover" style="border-bottom: 2px solid #00d4ff;">
                    <a href="index.php?page=media_detail&id=<?= $watch['id'] ?>">
                        <img src="<?= htmlspecialchars($watch['cover_image']) ?>" style="width: 100%; aspect-ratio: 2/3; object-fit: cover; opacity: 0.6;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>