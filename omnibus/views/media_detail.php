<?php
$media_id = (int)($_GET['id'] ?? 0);

// 1. Fetch Media Details
$stmt = $pdo->prepare("SELECT * FROM media WHERE id = ?");
$stmt->execute([$media_id]);
$item = $stmt->fetch();

if (!$item) {
    echo "<div class='app-container'><h2>Media not found.</h2></div>";
    exit;
}

// 2. Fetch User's Tracking Info (if logged in)
$user_entry = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM user_library WHERE user_id = ? AND media_id = ?");
    $stmt->execute([$_SESSION['user_id'], $media_id]);
    $user_entry = $stmt->fetch();
}
?>

<style>
    /* Base button style */
    .fav-btn {
        flex: 1;
        text-align: center;
        cursor: pointer;
        padding: 10px;
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-card);
        color: var(--text-muted);
    }

    /* Gold Hover Effect */
    .fav-btn:hover {
        border-color: #FFD700;
        color: #FFD700;
    }

    /* Gold Active Effect (Triggered on click via JS or initial PHP load) */
    .fav-btn.active {
        background: #FFD700 !important;
        color: #000 !important;
        font-weight: bold;
        border-color: #FFD700;
    }
</style>

<div class="app-container" style="margin-top: 2rem;">
    <div style="display: grid; grid-template-columns: 350px 1fr; gap: 4rem; align-items: start;">
        
        <aside>
            <div class="media-card" style="cursor: default; transform: none; margin-bottom: 2rem;">
                <img src="<?= htmlspecialchars($item['cover_image']) ?>" style="width: 100%; border-radius: var(--radius-md); box-shadow: var(--shadow-glow);">
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div style="background: var(--bg-card); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                    <h3 style="font-size: 1rem; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Personal Tracker</h3>
                    
                    <form action="index.php?page=media_detail&id=<?= $media_id ?>" method="POST" class="flex-col">
                        <input type="hidden" name="action" value="update_library">
                        <input type="hidden" name="media_id" value="<?= $media_id ?>">

                        <div class="form-group mb-3">
                            <label class="tag mb-2">Progress Status</label>
                            <select name="status" class="form-control">
                                <option value="">Not Tracked</option>
                                <option value="want" <?= ($user_entry['status'] ?? '') == 'want' ? 'selected' : '' ?>>Plan to Watch/Read</option>
                                <option value="consuming" <?= ($user_entry['status'] ?? '') == 'consuming' ? 'selected' : '' ?>>Currently In Progress</option>
                                <option value="completed" <?= ($user_entry['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed / Finished</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="tag mb-2">Score</label>
                            <select name="rating" class="form-control" style="color: #FFD700; font-weight: bold;">
                                <option value="">No Rating</option>
                                <?php for($i=5; $i>=1; $i--): ?>
                                    <option value="<?= $i ?>" <?= ($user_entry['rating'] ?? 0) == $i ? 'selected' : '' ?>>
                                        <?= str_repeat('★', $i) ?> (<?= $i ?>/5)
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="tag mb-2" style="color: #FFD700;">Curator's Top 5</label>
                            <div id="fav-container" style="display: flex; gap: 8px; background: rgba(0,0,0,0.3); padding: 6px; border-radius: var(--radius-sm); border: 1px solid var(--border-subtle);">
                                
                                <label class="fav-btn <?= ($user_entry['is_favorite'] ?? 0) == 1 ? 'active' : '' ?>">
                                    <input type="radio" name="is_favorite" value="1" <?= ($user_entry['is_favorite'] ?? 0) == 1 ? 'checked' : '' ?> style="display:none;" onclick="updateToggle(this)">
                                    Add
                                </label>

                                <label class="fav-btn <?= ($user_entry['is_favorite'] ?? 0) == 0 ? 'active' : '' ?>">
                                    <input type="radio" name="is_favorite" value="0" <?= ($user_entry['is_favorite'] ?? 0) == 0 ? 'checked' : '' ?> style="display:none;" onclick="updateToggle(this)">
                                    Don't Add
                                </label>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">Update Entry</button>
                    </form>
                </div>
            <?php endif; ?>
            
            <details style="margin-top: 1.5rem; color: var(--text-muted); cursor: pointer;">
                <summary style="font-size: 0.8rem;">Update Poster URL</summary>
                <form action="index.php?page=media_detail&id=<?= $media_id ?>" method="POST" style="margin-top: 10px;">
                    <input type="hidden" name="action" value="edit_media">
                    <input type="hidden" name="media_id" value="<?= $media_id ?>">
                    <input type="text" name="cover_image" placeholder="Paste new URL here..." class="form-control mb-2">
                    <button type="submit" class="btn btn-ghost" style="width: 100%; font-size: 0.7rem;">Update Image</button>
                </form>
            </details>
        </aside>

        <main>
            <div class="mb-4">
                <span class="tag <?= $item['type'] ?>"><?= strtoupper($item['type']) ?></span>
                <h1 class="text-gradient" style="font-size: 4rem; margin: 0.5rem 0; line-height: 1.1;"><?= htmlspecialchars($item['title']) ?></h1>
                <p style="font-size: 1.4rem; color: var(--text-muted); font-family: var(--font-heading);">
                    Directed/Written by <span style="color: white;"><?= htmlspecialchars($item['creator']) ?></span> • <?= $item['release_year'] ?>
                </p>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border-subtle); margin: 2.5rem 0;">

            <div class="content-body">
                <h3 class="mb-3" style="font-size: 1.2rem; letter-spacing: 1px; color: var(--primary);">SYNOPSIS</h3>
                <p style="font-size: 1.15rem; line-height: 1.8; color: var(--text-muted);">
                    <?= nl2br(htmlspecialchars($item['description'])) ?>
                </p>
            </div>

            <section class="reviews-section" style="margin-top: 3rem;">
                <h3 class="mb-4" style="letter-spacing: 1px;">CRITIC REVIEWS</h3>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="index.php?page=media_detail&id=<?= $media_id ?>" method="POST" class="mb-5">
                        <input type="hidden" name="action" value="add_review">
                        <input type="hidden" name="media_id" value="<?= $media_id ?>">
                        <textarea name="review_content" placeholder="Write your thoughts..." class="form-control mb-2" style="background: var(--bg-card); border: 1px solid var(--border-subtle); min-height: 100px;"></textarea>
                        <button type="submit" class="btn btn-ghost" style="font-size: 0.8rem;">Post Review</button>
                    </form>
                <?php endif; ?>

                <?php
                $review_stmt = $pdo->prepare("SELECT r.*, u.username, u.avatar_url FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.media_id = ? ORDER BY r.created_at DESC");
                $review_stmt->execute([$media_id]);
                $reviews = $review_stmt->fetchAll();

                if (empty($reviews)): ?>
                    <p style="color: var(--text-muted); font-style: italic;">No reviews yet.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                        <div style="background: var(--bg-card); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1rem; border: 1px solid var(--border-subtle);">
                            <div class="flex-row mb-2" style="gap: 10px; align-items: center;">
                                <img src="<?= $rev['avatar_url'] ?: 'https://ui-avatars.com/api/?name='.$rev['username'] ?>" style="width: 30px; height: 30px; border-radius: 50%;">
                                <span style="font-weight: bold; font-size: 0.9rem; color: var(--primary);"><?= htmlspecialchars($rev['username']) ?></span>
                                <span style="font-size: 0.7rem; color: var(--text-muted);"><?= date('M d, Y', strtotime($rev['created_at'])) ?></span>
                            </div>
                            <p style="color: var(--text-muted); line-height: 1.6;"><?= nl2br(htmlspecialchars($rev['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>

<script>
function updateToggle(radio) {
    // 1. Get the container and all labels inside it
    const container = document.getElementById('fav-container');
    const labels = container.querySelectorAll('.fav-btn');
    
    // 2. Clear 'active' from all buttons
    labels.forEach(l => l.classList.remove('active'));
    
    // 3. Add 'active' to the parent label of the clicked radio
    radio.parentElement.classList.add('active');
}
</script>