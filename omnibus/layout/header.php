<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Omnibus | The Unified Media Library</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Merriweather:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <a href="index.php?page=admin_add" class="btn btn-ghost" style="color: var(--primary); border: 1px solid var(--primary);">+ Add Media</a>
</head>
<body>

<nav class="main-nav">
    <a href="index.php?page=home" class="nav-logo">OMNIBUS.</a>
    
    <form action="index.php" method="GET" style="flex-grow: 1; max-width: 400px; margin: 0 2rem;">
        <input type="hidden" name="page" value="browse">
        <input type="text" name="search" placeholder="Search books, movies, shows..." 
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
               style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-subtle); border-radius: 20px; padding: 0.5rem 1.5rem; color: white; width: 100%;">
    </form>
    
    <div class="nav-links flex-row">
        <?php if(is_logged_in()): ?>
            <a href="index.php?page=browse" class="btn btn-ghost" style="border:none;">Browse</a>
            <a href="index.php?page=dashboard" class="btn btn-ghost" style="border:none;">Dashboard</a>
            <a href="index.php?page=profile" class="btn btn-ghost" style="border:none;">Profile</a>
            <a href="index.php?page=logout" class="btn btn-primary" style="margin-left: 10px;">Logout</a>
        <?php else: ?>
            <a href="index.php?page=browse" class="btn btn-ghost" style="border:none;">Browse</a>
            <a href="index.php?page=login" class="btn btn-ghost">Login</a>
            <a href="index.php?page=register" class="btn btn-primary">Get Started</a>
        <?php endif; ?>
    </div>
</nav>

<div class="nav-links flex-row">
    <a href="index.php?page=browse" class="btn btn-ghost">Browse</a>
    
    <?php if(is_logged_in()): ?>
        <a href="index.php?page=add_media" style="color: var(--primary); font-weight: bold; margin: 0 10px;">+ Add New</a>
        
        <a href="index.php?page=dashboard" class="btn btn-ghost">Dashboard</a>
        <a href="index.php?page=logout" class="btn btn-primary">Logout</a>
    <?php else: ?>
        <a href="index.php?page=login" class="btn btn-ghost">Login</a>
        <a href="index.php?page=register" class="btn btn-primary">Start Archive</a>
    <?php endif; ?>
</div>

<div class="app-container">