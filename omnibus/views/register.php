<div style="max-width: 450px; margin: 4rem auto;">
    <div style="background: var(--bg-card); padding: 3rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle); box-shadow: var(--shadow-soft);">
        <h2 class="text-gradient" style="font-size: 2.5rem; margin-bottom: 1rem;">Join the Archive.</h2>
        <p style="color: var(--text-muted); margin-bottom: 2rem;">Create your unified library for books, cinema, and television.</p>

        <form action="index.php?page=register" method="POST" class="flex-col">
            <div class="form-group">
                <label class="tag" style="background: transparent; padding-left: 0;">Username</label>
                <input type="text" name="username" required placeholder="cinephile_99">
            </div>
            
            <div class="form-group">
                <label class="tag" style="background: transparent; padding-left: 0;">Email</label>
                <input type="email" name="email" required placeholder="you@example.com">
            </div>

            <div class="form-group">
                <label class="tag" style="background: transparent; padding-left: 0;">Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Initialize Account</button>
        </form>
        
        <p style="margin-top: 1.5rem; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
            Already a member? <a href="index.php?page=login" style="color: var(--primary); font-weight: 600;">Sign In</a>
        </p>
    </div>
</div>