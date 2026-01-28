<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="text-gradient mb-2">Archive Ingestion</h1>
    <p class="mb-4" style="color: var(--text-muted);">Add new high-fidelity entries to the Omnibus database.</p>

    <form action="index.php?page=admin_add" method="POST" class="flex-col" style="background: var(--bg-card); padding: 3rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
        <input type="hidden" name="action" value="add_media">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div class="form-group">
                <label class="tag">Media Type</label>
                <select name="type" required>
                    <option value="movie">Movie</option>
                    <option value="book">Book</option>
                    <option value="show">TV Show</option>
                </select>
            </div>
            <div class="form-group">
                <label class="tag">Release Year</label>
                <input type="number" name="release_year" placeholder="2024" required>
            </div>
        </div>

        <div class="form-group">
            <label class="tag">Title</label>
            <input type="text" name="title" placeholder="The title of the work" required>
        </div>

        <div class="form-group">
            <label class="tag">Creator / Director / Author</label>
            <input type="text" name="creator" placeholder="Who made it?" required>
        </div>

        <div class="form-group">
            <label class="tag">Cover Image URL</label>
            <input type="text" name="cover_image" placeholder="https://..." required>
        </div>

        <div class="form-group">
            <label class="tag">Description / Synopsis</label>
            <textarea name="description" rows="5" placeholder="What is this story about?"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Add to Archive</button>
    </form>
</div>