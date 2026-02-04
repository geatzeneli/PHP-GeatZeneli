<?php
// 1. Fetch Aggregated Stats
$stats = get_user_stats($pdo, $_SESSION['user_id']);

// 2. Fetch Media Lists for the Shelves
$currently_consuming = get_user_library_by_status($pdo, $_SESSION['user_id'], 'consuming');
$watchlist = get_user_library_by_status($pdo, $_SESSION['user_id'], 'want');

// 3. Fetch the 5 most recent activities for the timeline
$stmt = $pdo->prepare("
    SELECT a.*, m.title, m.type 
    FROM activity_log a 
    JOIN media m ON a.media_id = m.id 
    WHERE a.user_id = ? 
    ORDER BY a.created_at DESC LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$activities = $stmt->fetchAll();
?>

<div class="dashboard-stats flex-row" style="justify-content: space-between; margin-bottom: 3rem; flex-wrap: wrap; gap: 1.5rem;">
    <div class="stat-card">
        <span class="tag">Total Archive</span>
        <div style="font-size: 2.8rem; font-weight: 800; font-family: var(--font-heading);"><?= $stats['total'] ?? 0 ?></div>
    </div>
    <div class="stat-card">
        <span class="tag book">Books Read</span>
        <div style="font-size: 2.8rem; font-weight: 800; font-family: var(--font-heading);"><?= $stats['books_read'] ?? 0 ?></div>
    </div>
    <div class="stat-card">
        <span class="tag movie">Movies Seen</span>
        <div style="font-size: 2.8rem; font-weight: 800; font-family: var(--font-heading);"><?= $stats['movies_watched'] ?? 0 ?></div>
    </div>
    <div class="stat-card">
        <span class="tag show">Shows Finished</span>
        <div style="font-size: 2.8rem; font-weight: 800; font-family: var(--font-heading);"><?= $stats['shows_finished'] ?? 0 ?></div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 350px; gap: 3rem;">
    
    <div>
        <section class="mb-4">
            <h2 style="margin-bottom: 1.5rem; border-left: 4px solid var(--primary); padding-left: 1rem;">In Progress</h2>
            <?php if (empty($currently_consuming)): ?>
                <div style="padding: 3rem; background: var(--glass); border: 1px dashed var(--border-subtle); border-radius: var(--radius-md); text-align: center; color: var(--text-muted);">
                    Nothing in progress. <a href="index.php?page=browse" style="color: var(--primary);">Discover something new.</a>
                </div>
            <?php else: ?>
                <div class="grid-media" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));">
                    <?php foreach ($currently_consuming as $item): ?>
                        <div class="media-card">
                            <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                                <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster" alt="Poster">
                                <div class="media-info">
                                    <span class="tag <?= $item['type'] ?>"><?= $item['type'] ?></span>
                                    <h4 style="font-size: 0.9rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($item['title']) ?></h4>
                                    <div style="background: rgba(255,255,255,0.05); height: 4px; border-radius: 2px; overflow: hidden;">
                                        <div style="width: 65%; height: 100%; background: var(--primary); box-shadow: 0 0 8px var(--primary-glow);"></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section>
            <h2 style="margin-bottom: 1.5rem; border-left: 4px solid var(--text-muted); padding-left: 1rem;">The Queue</h2>
            <?php if (empty($watchlist)): ?>
                <p style="color: var(--text-muted);">Your watchlist is empty.</p>
            <?php else: ?>
                <div class="grid-media" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));">
                    <?php foreach ($watchlist as $item): ?>
                        <div class="media-card">
                            <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                                <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster" alt="Poster">
                                <div class="media-info">
                                    <span class="tag <?= $item['type'] ?>"><?= $item['type'] ?></span>
                                    <h4 style="font-size: 0.9rem;"><?= htmlspecialchars($item['title']) ?></h4>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <aside>
        <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); position: sticky; top: 100px;">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Recent Activity</h3>
            
            <?php if (empty($activities)): ?>
                <p style="color: var(--text-muted); font-size: 0.9rem;">No recent logs found.</p>
            <?php else: ?>
                <div class="flex-col" style="gap: 1.5rem;">
                    <?php foreach ($activities as $act): ?>
                        <div style="font-size: 0.9rem; border-left: 2px solid var(--border-subtle); padding-left: 1rem; position: relative;">
                            <div style="position: absolute; left: -5px; top: 5px; width: 8px; height: 8px; background: var(--primary); border-radius: 50%; box-shadow: 0 0 5px var(--primary-glow);"></div>
                            
                            <div style="color: var(--text-muted); font-size: 0.75rem; margin-bottom: 0.2rem;">
                                <?= date('M d, g:i a', strtotime($act['created_at'])) ?>
                            </div>
                            <div>
                                You marked <strong><?= htmlspecialchars($act['title']) ?></strong> 
                                as <span style="color: var(--primary);"><?= $act['action_type'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <a href="index.php?page=profile" class="btn btn-ghost" style="width: 100%; margin-top: 2rem; font-size: 0.8rem;">View Full History</a>
        </div>
    </aside>

</div>

<?php
// Fetch global activity instead of just the user's
$stmt = $pdo->query("
    SELECT a.*, u.username, m.title, m.cover_image 
    FROM activity_log a 
    JOIN users u ON a.user_id = u.id 
    JOIN media m ON a.media_id = m.id 
    ORDER BY a.created_at DESC LIMIT 10
");
$global_activity = $stmt->fetchAll();
?>

<div class="activity-feed">
    <?php foreach ($global_activity as $act): ?>
        <div class="activity-item" style="display: flex; gap: 1rem; margin-bottom: 1rem; align-items: center;">
            <img src="<?= htmlspecialchars($act['cover_image']) ?>" style="width: 40px; height: 60px; object-fit: cover; border-radius: 4px;">
            <p style="font-size: 0.9rem;">
                <strong style="color: var(--primary);"><?= htmlspecialchars($act['username']) ?></strong> 
                <?= htmlspecialchars($act['action_type']) ?> 
                <strong style="color: white;"><?= htmlspecialchars($act['title']) ?></strong>
            </p>
        </div>
    <?php endforeach; ?>
</div>

<style>
/* Dashboard specific card styling override */
.stat-card {
    background: linear-gradient(145deg, var(--bg-card), var(--bg-surface));
    padding: 1.5rem 2rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-subtle);
    flex: 1;
    min-width: 220px;
    box-shadow: var(--shadow-soft);
    position: relative;
    overflow: hidden;
}
.stat-card .tag { margin-bottom: 1rem; }
</style>