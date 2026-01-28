<?php
// includes/functions.php

/**
 * Get aggregated stats for the user dashboard
 */
function get_user_stats($pdo, $user_id) {
    $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as finished,
                SUM(CASE WHEN type = 'book' AND status = 'completed' THEN 1 ELSE 0 END) as books_read,
                SUM(CASE WHEN type = 'movie' AND status = 'completed' THEN 1 ELSE 0 END) as movies_watched,
                SUM(CASE WHEN type = 'show' AND status = 'completed' THEN 1 ELSE 0 END) as shows_finished
            FROM user_library ul
            JOIN media m ON ul.media_id = m.id
            WHERE ul.user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

/**
 * Fetch items based on status (e.g., 'consuming')
 */
function get_user_library_by_status($pdo, $user_id, $status) {
    $sql = "SELECT m.*, ul.progress, ul.rating, ul.status
            FROM user_library ul
            JOIN media m ON ul.media_id = m.id
            WHERE ul.user_id = ? AND ul.status = ?
            ORDER BY ul.last_updated DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $status]);
    return $stmt->fetchAll();
}