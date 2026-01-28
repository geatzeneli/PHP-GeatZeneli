<?php
$stmt = $pdo->prepare("SELECT * FROM media WHERE id = ?");
$stmt->execute([$id]);
$media = $stmt->fetch();

if (!$media) {
    echo "<div class='app-container'><h1>Media not found.</h1></div>";
    return;
}

// Check if user already has this in their library
$lib_stmt = $pdo->prepare("SELECT * FROM user_library WHERE user_id = ? AND media_id = ?");
$lib_stmt->execute([$_SESSION['user_id'] ?? 0, $media['id']]);
$user_entry = $lib_stmt->fetch();
?>

<div style="position: absolute; top: 0; left: 0; width: 100%; height: 70vh; z-index: -1; overflow: hidden; mask-image: linear-gradient(to bottom, black 20%, transparent 100%);">
    <img src="<?= htmlspecialchars($media['cover_image']) ?>" style="width: 100%; height: 100%; object-fit: cover; filter: blur(60px) brightness(0.3); transform: scale(1.1);">
</div>

<div class="app-container" style="display: grid; grid-template-columns: 320px 1fr; gap: 4rem; padding-top: 5vh;">
    
    <aside>
        <img src="<?= htmlspecialchars($media['cover_image']) ?>" style="width: 100%; border-radius: var(--radius-md); box-shadow: 0 30px 60px rgba(0,0,0,0.8); border: 1px solid var(--border-subtle);">
        
        <?php if (isset($_SESSION['user_id'])): ?>
        <div style="margin-top: 2rem; background: var(--bg-card); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
            <h3 style="font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.5rem; color: var(--text-muted);">Your Library</h3>
            
            <form action="index.php?page=media_detail&id=<?= $media['id'] ?>" method="POST" class="flex-col">
    <input type="hidden" name="action" value="update_library">
    <input type="hidden" name="media_id" value="<?= $media['id'] ?>">
    
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>
            <form action="index.php?page=media_detail&id=<?= $media['id'] ?>" method="POST" class="flex-col">
                <input type="hidden" name="action" value="update_library">
                <input type="hidden" name="media_id" value="<?= $media['id'] ?>">
                
                <div class="form-group">
                    <label style="font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">Status</label>
                    <select name="status">
                        <option value="">Not Tracked</option>
                        <option value="want" <?= ($user_entry['status'] ?? '') === 'want' ? 'selected' : '' ?>>Want to <?= $media['type'] === 'book' ? 'Read' : 'Watch' ?></option>
                        <option value="consuming" <?= ($user_entry['status'] ?? '') === 'consuming' ? 'selected' : '' ?>>Currently <?= $media['type'] === 'book' ? 'Reading' : 'Watching' ?></option>
                        <option value="completed" <?= ($user_entry['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="dropped" <?= ($user_entry['status'] ?? '') === 'dropped' ? 'selected' : '' ?>>Dropped</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">Rating (1-5)</label>
                    <input type="number" name="rating" min="1" max="5" value="<?= $user_entry['rating'] ?? '' ?>" placeholder="★ ★ ★ ★ ★">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
            </form>
        </div>
        <?php else: ?>
            <a href="index.php?page=login" class="btn btn-ghost" style="width: 100%; margin-top: 2rem;">Login to track this</a>
        <?php endif; ?>
    </aside>

    <article>
        <span class="tag <?= $media['type'] ?>" style="padding: 0.5rem 1.5rem; font-size: 0.9rem;"><?= $media['type'] ?></span>
        <h1 style="font-size: 4.5rem; line-height: 1; margin: 1rem 0;"><?= htmlspecialchars($media['title']) ?></h1>
        
        <div class="flex-row mb-4" style="font-family: var(--font-heading); font-size: 1.2rem; color: var(--text-muted);">
            <span><?= $media['release_year'] ?></span>
            <span>•</span>
            <span>Directed/Written by <?= htmlspecialchars($media['creator']) ?></span>
        </div>

        <div style="font-size: 1.2rem; line-height: 1.8; color: #cbd5e1; max-width: 70ch; background: rgba(0,0,0,0.2); padding: 2rem; border-radius: var(--radius-md);">
            <?= nl2br(htmlspecialchars($media['description'])) ?>
        </div>
    </article>
</div>