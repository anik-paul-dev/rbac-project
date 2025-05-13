<?php
require_once '../config/db.php';
require_once '../middleware/auth.php';
require_once '../controllers/UserController.php';
require_once '../controllers/BlogController.php';

header('Content-Type: application/json');

// Get JWT from Authorization header
$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;
$userId = $token ? verifyJWT($token) : null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$role = $user['role'];

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? $_GET['path'] : '';

$userController = new UserController();
$blogController = new BlogController();

switch ($path) {
    case 'users':
        if ($method === 'GET' && $role === 'admin') {
            echo json_encode($userController->getAllUsers());
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
        break;

    case 'user/approve':
        if ($method === 'POST' && $role === 'admin') {
            $data = json_decode(file_get_contents('php://input'), true);
            $userId = isset($data['id']) ? (int)$data['id'] : 0;
            if ($userController->approveUser($userId)) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Failed to approve user']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
        break;

    case 'posts':
        if ($method === 'GET') {
            echo json_encode($blogController->getPosts());
        } elseif ($method === 'POST' && hasPermission('add_posts')) {
            $data = json_decode(file_get_contents('php://input'), true);
            $title = isset($data['title']) ? $data['title'] : '';
            $content = isset($data['content']) ? $data['content'] : '';
            if ($blogController->addPost($title, $content, $userId)) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Failed to add post']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
}
?>