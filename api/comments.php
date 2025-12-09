<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Get comments for a post
if ($action === 'get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postId = $_GET['post_id'] ?? '';
    
    if (empty($postId)) {
        echo json_encode(['success' => false, 'message' => 'Post ID required']);
        exit;
    }
    
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT c.id, c.comment, c.created_at, u.name, u.username 
                           FROM comments c 
                           JOIN users u ON c.user_id = u.id 
                           WHERE c.post_id = ? 
                           ORDER BY c.created_at DESC");
    $stmt->bind_param("s", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    
    echo json_encode(['success' => true, 'comments' => $comments]);
    
    $stmt->close();
    $conn->close();
}

// Add a new comment
elseif ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Please login to comment']);
        exit;
    }
    
    $postId = $_POST['post_id'] ?? '';
    $comment = trim($_POST['comment'] ?? '');
    
    if (empty($postId) || empty($comment)) {
        echo json_encode(['success' => false, 'message' => 'Post ID and comment are required']);
        exit;
    }
    
    $conn = getDbConnection();
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $postId, $_SESSION['user_id'], $comment);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Comment added successfully',
            'comment' => [
                'name' => $_SESSION['name'],
                'username' => $_SESSION['username'],
                'comment' => $comment,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
