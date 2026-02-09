<?php
// 1. DATA PREPARATION
$user_id = $_SESSION['user_id'];

// Make sure these function names match your includes/functions.php
$finished_items      = get_user_library_by_status($pdo, $user_id, 'completed');
$currently_consuming = get_user_library_by_status($pdo, $user_id, 'consuming');
$watchlist           = get_user_library_by_status($pdo, $user_id, 'want');

$stats = get_user_stats($pdo, $user_id);
?>

<div class="dashboard-stats flex-row" style="display: flex; gap: 1.5rem; margin-bottom: 3rem; justify-content: space-between;">
    <div class="stat-card" style="background: var(--bg-card); padding: 1.5rem; border-radius: 8px; flex: 1; border: 1px solid var(--border-subtle);">
        <span class="tag">Total Archive</span>
        <div style="font-size: 2rem; font-weight: 800;"><?= $stats['total'] ?? 0 ?></div>
    </div>
    <div class="stat-card" style="background: var(--bg-card); padding: 1.5rem; border-radius: 8px; flex: 1; border: 1px solid var(--border-subtle);">
        <span class="tag book">Books Read</span>
        <div style="font-size: 2rem; font-weight: 800;"><?= $stats['books_read'] ?? 0 ?></div>
    </div>
    <div class="stat-card" style="background: var(--bg-card); padding: 1.5rem; border-radius: 8px; flex: 1; border: 1px solid var(--border-subtle);">
        <span class="tag movie">Movies Seen</span>
        <div style="font-size: 2rem; font-weight: 800;"><?= $stats['movies_watched'] ?? 0 ?></div>
    </div>
</div>

<div class="app-container">

    <?php if (!empty($finished_items)): ?>
    <section class="dashboard-shelf" style="margin-bottom: 4rem;">
        <div class="shelf-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 class="shelf-title" style="border-left: 4px solid #4ade80; padding-left: 1rem; font-size: 1.2rem; color: #4ade80;">FINISHED SHELF</h2>
            <span class="tag" style="background: rgba(74, 222, 128, 0.1); color: #4ade80;"><?= count($finished_items) ?> ITEMS</span>
        </div>
        <div class="grid-media" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.5rem;">
            <?php foreach ($finished_items as $item): ?>
                <div class="media-card">
                    <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster" style="width: 100%; border-radius: 8px;">
                        <div class="media-info" style="margin-top: 10px;">
                            <h4 style="font-size: 0.9rem;"><?= htmlspecialchars($item['title']) ?></h4>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($currently_consuming)): ?>
    <section class="dashboard-shelf" style="margin-bottom: 4rem;">
        <div class="shelf-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 class="shelf-title" style="border-left: 4px solid var(--primary); padding-left: 1rem; font-size: 1.2rem; color: var(--primary);">CURRENTLY IN PROGRESS</h2>
            <span class="tag" style="background: var(--primary-glow); color: var(--primary);"><?= count($currently_consuming) ?> ITEMS</span>
        </div>
        <div class="grid-media" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.5rem;">
            <?php foreach ($currently_consuming as $item): ?>
                <div class="media-card">
                    <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster" style="width: 100%; border-radius: 8px;">
                        <div class="media-info" style="margin-top: 10px;">
                            <h4 style="font-size: 0.9rem; margin-bottom: 5px;"><?= htmlspecialchars($item['title']) ?></h4>
                            <div style="background: rgba(255,255,255,0.1); height: 4px; border-radius: 2px;">
                                <div style="width: 65%; height: 100%; background: var(--primary);"></div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($watchlist)): ?>
    <section class="dashboard-shelf">
        <div class="shelf-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 class="shelf-title" style="border-left: 4px solid #00d4ff; padding-left: 1rem; font-size: 1.2rem; color: #00d4ff;">WATCHLIST / PLANNED</h2>
            <span class="tag" style="background: rgba(0, 212, 255, 0.1); color: #00d4ff;"><?= count($watchlist) ?> ITEMS</span>
        </div>
        <div class="grid-media" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.5rem;">
            <?php foreach ($watchlist as $item): ?>
                <div class="media-card">
                    <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster" style="width: 100%; border-radius: 8px;">
                        <div class="media-info" style="margin-top: 10px;">
                            <h4 style="font-size: 0.9rem;"><?= htmlspecialchars($item['title']) ?></h4>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

</div>