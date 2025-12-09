<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = trim($_GET['q'] ?? '');
    
    if (empty($query)) {
        echo json_encode(['success' => false, 'message' => 'Search query is required']);
        exit;
    }
    
    $conn = getDbConnection();
    $searchTerm = "%{$query}%";
    $stmt = $conn->prepare("SELECT id, slug, title, excerpt, image_url, created_at 
                           FROM posts 
                           WHERE title LIKE ? OR content LIKE ? OR excerpt LIKE ?
                           ORDER BY created_at DESC");
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    
    echo json_encode(['success' => true, 'posts' => $posts, 'count' => count($posts)]);
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
