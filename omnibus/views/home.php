<div class="app-container" style="text-align: center; padding-top: 10vh;">
    <h1 class="text-gradient" style="font-size: 5rem; margin-bottom: 1rem; line-height: 1;">Track Everything.</h1>
    <p style="font-size: 1.5rem; color: var(--text-muted); max-width: 600px; margin: 0 auto 3rem auto; font-family: var(--font-heading); font-style: italic;">
        The unified archive for your books, films, and television shows.
    </p>

    <div class="flex-row" style="justify-content: center; gap: 2rem;">
        <a href="index.php?page=register" class="btn btn-primary" style="padding: 1.5rem 3rem; font-size: 1.1rem;">Start Your Archive</a>
        <a href="index.php?page=browse" class="btn btn-ghost" style="padding: 1.5rem 3rem; font-size: 1.1rem;">Browse Media</a>
    </div>

    <div style="margin-top: 6rem; opacity: 0.5;" class="flex-row; justify-content: center;">
        <span class="tag book">Books</span>
        <span class="tag movie">Movies</span>
        <span class="tag show">TV Shows</span>
    </div>
</div>

<div class="app-container" style="margin-top: 4rem;">
    <p style="text-align: center; color: var(--text-muted); font-family: var(--font-heading); margin-bottom: 2rem;">JOIN THE COLLECTIVE ARCHIVE</p>
    
    <div class="flex-row" style="justify-content: center; gap: 1.5rem; overflow: hidden; opacity: 0.6;">
        <?php
        $preview = $pdo->query("SELECT cover_image FROM media ORDER BY RAND() LIMIT 6")->fetchAll();
        foreach ($preview as $img): ?>
            <img src="<?= $img['cover_image'] ?>" style="width: 140px; height: 210px; object-fit: cover; border-radius: var(--radius-md); box-shadow: var(--shadow-soft);">
        <?php endforeach; ?>
    </div>
</div>