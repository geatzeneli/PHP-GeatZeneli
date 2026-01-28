<div style="max-width: 400px; margin: 6rem auto;">
    <div style="background: var(--bg-card); padding: 3rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
        <h2 class="mb-2">Welcome Back.</h2>
        <p class="mb-4" style="color: var(--text-muted);">Sign in to access your library.</p>

        <?php if (isset($_GET['registered'])): ?>
            <p style="color: var(--success); margin-bottom: 1rem; font-size: 0.9rem;">Registration successful! Please login.</p>
        <?php endif; ?>

        <form action="index.php?page=login" method="POST" class="flex-col">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
        
        <p style="margin-top: 2rem; text-align: center; font-size: 0.9rem;">
            New here? <a href="index.php?page=register" style="color: var(--primary);">Create an account</a>
        </p>
    </div>
</div>