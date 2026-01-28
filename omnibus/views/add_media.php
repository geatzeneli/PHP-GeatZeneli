<div style="max-width: 700px; margin: 0 auto; padding-top: 2rem;">
    <h1 class="text-gradient mb-2">Contribute to the Archive</h1>
    <p class="mb-4" style="color: var(--text-muted);">Can't find what you're looking for? Add it to the Omnibus for everyone.</p>

    <form action="index.php?page=add_media" method="POST" class="flex-col" style="background: var(--bg-card); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
        <input type="hidden" name="action" value="user_add_media">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="tag">Type</label>
                <select name="type" required>
                    <option value="movie">Movie</option>
                    <option value="book">Book</option>
                    <option value="show">TV Show</option>
                </select>
            </div>
            <div class="form-group">
                <label class="tag">Release Year</label>
                <input type="number" name="release_year" placeholder="YYYY" required min="1800" max="2030">
            </div>
        </div>

        <div class="form-group">
            <label class="tag">Title</label>
            <input type="text" name="title" placeholder="Full title of the work" required>
        </div>

        <div class="form-group">
            <label class="tag">Creator</label>
            <input type="text" name="creator" placeholder="Director, Author, or Showrunner" required>
        </div>

        <div class="form-group">
            <label class="tag">Cover Image URL</label>
            <input type="url" name="cover_image" placeholder="https://path-to-image.jpg" required>
            <small style="color: var(--text-muted); font-size: 0.75rem; margin-top: 5px; display: block;">Pro tip: Use a vertical poster/cover link.</small>
        </div>

        <div class="form-group">
            <label class="tag">Description</label>
            <textarea name="description" rows="4" placeholder="Brief summary of the plot or premise..." required></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Add to Library</button>
    </form>
</div>