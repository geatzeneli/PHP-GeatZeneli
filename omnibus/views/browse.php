<?php

// 1. DATA LOGIC: Fetch Recently Added (Top 4)
$recent_stmt = $pdo->query("SELECT * FROM media ORDER BY id DESC LIMIT 4");
$recently_added = $recent_stmt->fetchAll();

// 2. DATA LOGIC: Handle Filters and Search
$search = $_GET['search'] ?? '';
$filter = $_GET['type'] ?? 'all';

$query = "SELECT * FROM media WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (title LIKE ? OR creator LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($filter !== 'all') {
    $query .= " AND type = ?";
    $params[] = $filter;
}

$query .= " ORDER BY release_year DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>

<div class="browse-hero" style="padding: 3rem 2rem; background: var(--glass); border-radius: var(--radius-lg); border: 1px solid var(--border-subtle); margin-bottom: 3rem; text-align: center;">
    <h1 class="text-gradient" style="font-size: 2.5rem; margin-bottom: 1rem;">Your Personal Omnibus</h1>
    <p style="color: var(--text-muted); max-width: 700px; margin: 0 auto 1.5rem auto; line-height: 1.6; font-size: 1.1rem;">
        Your own media museum.

This is your personal archive of the stories that define you. Use the Watchlist to queue up the movies, shows, and books you can’t wait to experience next. Once finished, honor the true masterpieces by rating them 1 to 5 stars and adding your own critique. If you find something new to preserve, simply add it to the collection.
    </p>
    
    <div style="display: flex; gap: 1rem; justify-content: center; align-items: center;">
        <span style="color: var(--text-muted); font-size: 0.9rem;">Want to expand your collection?</span>
        <a href="index.php?page=add_media" class="btn btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.9rem;">
            + Add New Media
        </a>
    </div>
</div>
<section class="mb-5" style="background: rgba(255,255,255,0.02); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
    <div class="flex-row mb-3" style="justify-content: space-between; align-items: center;">
        <h2 style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px; color: var(--primary); font-weight: 700;">New in the Archive</h2>
        <a href="index.php?page=add_media" style="font-size: 0.8rem; color: var(--text-muted); text-decoration: none; border: 1px solid var(--border-subtle); padding: 5px 12px; border-radius: 20px;">+ Contribute</a>
    </div>

    <div class="grid-media" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
        <?php foreach ($recently_added as $recent): ?>
            <a href="index.php?page=media_detail&id=<?= $recent['id'] ?>" style="display: flex; gap: 1rem; align-items: center; text-decoration: none; background: var(--bg-card); padding: 0.5rem; border-radius: var(--radius-md);">
                <img src="<?= htmlspecialchars($recent['cover_image']) ?>" style="width: 50px; height: 75px; object-fit: cover; border-radius: 4px;">
                <div style="overflow: hidden;">
                    <h4 style="font-size: 0.85rem; margin-bottom: 0.2rem; color: white; white-space: nowrap; text-overflow: ellipsis;"><?= htmlspecialchars($recent['title']) ?></h4>
                    <span class="tag <?= $recent['type'] ?>" style="font-size: 0.6rem; padding: 2px 6px;"><?= $recent['type'] ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<hr style="border: 0; border-top: 1px solid var(--border-subtle); margin: 3rem 0;">

<header class="mb-4">
    <h1 class="text-gradient" style="font-size: 3rem;">
        <?= !empty($search) ? 'Search Results' : 'The Library' ?>
    </h1>
</header>

<div class="flex-row mb-4" style="border-bottom: 1px solid var(--border-subtle); padding-bottom: 1rem; gap: 2rem;">
    <a href="index.php?page=browse&type=all" class="<?= $filter === 'all' ? 'nav-active' : '' ?>" style="text-decoration: none; font-weight: 700; font-size: 0.9rem; color: var(--text-muted);">ALL</a>
    <a href="index.php?page=browse&type=book" class="<?= $filter === 'book' ? 'nav-active' : '' ?>" style="text-decoration: none; font-weight: 700; font-size: 0.9rem; color: var(--text-muted);">BOOKS</a>
    <a href="index.php?page=browse&type=movie" class="<?= $filter === 'movie' ? 'nav-active' : '' ?>" style="text-decoration: none; font-weight: 700; font-size: 0.9rem; color: var(--text-muted);">MOVIES</a>
    <a href="index.php?page=browse&type=show" class="<?= $filter === 'show' ? 'nav-active' : '' ?>" style="text-decoration: none; font-weight: 700; font-size: 0.9rem; color: var(--text-muted);">TV SHOWS</a>
</div>

<div class="grid-media">
    <?php if (empty($items)): ?>
        <div style="grid-column: 1/-1; padding: 5rem; text-align: center; color: var(--text-muted); background: var(--bg-card); border-radius: var(--radius-md); border: 1px dashed var(--border-subtle);">
            <p style="font-size: 1.2rem;">No entries found in the Omnibus.</p>
            <a href="index.php?page=add_media" style="color: var(--primary); display: block; margin-top: 1rem;">Be the first to add this work.</a>
        </div>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <div class="media-card">
                <a href="index.php?page=media_detail&id=<?= $item['id'] ?>">
                    <img src="<?= htmlspecialchars($item['cover_image']) ?>" class="media-poster" alt="Cover">
                    <div class="media-info">
                        <span class="tag <?= $item['type'] ?>"><?= $item['type'] ?></span>
                        <h4 style="margin: 0.5rem 0 0.2rem 0; font-size: 1.1rem;"><?= htmlspecialchars($item['title']) ?></h4>
                        <p style="color: var(--text-muted); font-size: 0.85rem;"><?= htmlspecialchars($item['creator']) ?> (<?= $item['release_year'] ?>)</p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .nav-active { 
        color: var(--primary) !important; 
        border-bottom: 2px solid var(--primary); 
        padding-bottom: 1.1rem; 
    }
</style>