<?php
require_once '../controllers/UserController.php';
require_once '../middleware/auth.php';
restrictAccess('admin');

header('Content-Type: application/json');

$userController = new UserController();
$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($userId > 0 && in_array($action, ['approve', 'reject'])) {
        $success = $action === 'approve' ? $userController->approveUser($userId) : $userController->rejectUser($userId);
        $response['success'] = $success;
        $response['message'] = $success ? "User $action successfully." : "Failed to $action user.";
    } else {
        $response['message'] = 'Invalid request.';
    }
}

echo json_encode($response);
?>