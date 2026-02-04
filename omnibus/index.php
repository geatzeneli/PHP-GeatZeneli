<?php
// 1. INITIALIZE & LOAD CORE

session_start();
echo "";
require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// 2. ROUTING LOGIC
// We grab 'page' from the URL (index.php?page=dashboard). Default is 'home'.
$page = $_GET['page'] ?? 'home';
$id   = $_GET['id'] ?? null;

// 3. GLOBAL ACTION HANDLER
// This handles form submissions (Login, Register, Log Media) before any HTML is sent.
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $page === 'logout') {
    require_once 'includes/actions.php';
}

// 4. SECURITY GATE
// Prevent logged-out users from seeing private pages.
$protected_pages = ['dashboard', 'profile', 'settings'];
if (in_array($page, $protected_pages) && !is_logged_in()) {
    header("Location: index.php?page=login");
    exit;
}

// 5. THEME & VIEW LOADING
// This is where the visual "Body" is built.
require_once 'layout/header.php';

echo "<main class='app-wrapper'>";
    
    $view_file = "views/{$page}.php";
    
    if (file_exists($view_file)) {
        require_once $view_file;
    } else {
        // Fallback for 404
        echo "<div class='app-container'><h1>404</h1><p>The archive section '{$page}' doesn't exist.</p></div>";
    }

echo "</main>";

require_once 'layout/footer.php';