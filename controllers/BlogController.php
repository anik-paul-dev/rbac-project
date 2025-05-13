<?php
require_once '../config/db.php';
require_once '../middleware/auth.php';

class BlogController {
    public function addPost($title, $content, $userId) {
        global $conn;
        if (!hasPermission('add_posts')) return false;
        $query = "INSERT INTO blog_posts (title, content, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $title, $content, $userId);
        return $stmt->execute();
    }

    public function getPosts() {
        global $conn;
        $query = "SELECT * FROM blog_posts";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deletePost($postId) {
        global $conn;
        if (!hasPermission('manage_posts')) return false;
        $query = "DELETE FROM blog_posts WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postId);
        return $stmt->execute();
    }
}
?>