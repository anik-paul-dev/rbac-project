<?php
require_once '../config/db.php';
require_once '../middleware/auth.php';
require_once '../notifications/send_approval_notice.php';

class UserController {
    public function getPendingUsers($search = '', $roleFilter = '') {
        global $conn;
        $query = "SELECT * FROM users WHERE status = 'pending'";
        $params = [];
        $types = '';

        if ($search) {
            $query .= " AND (name LIKE ? OR email LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'ss';
        }
        if ($roleFilter) {
            $query .= " AND role = ?";
            $params[] = $roleFilter;
            $types .= 's';
        }

        $stmt = $conn->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function approveUser($userId) {
        global $conn;
        $query = "UPDATE users SET status = 'approved' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $query = "SELECT email, name FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            sendApprovalNotice($user['email'], $user['name'], 'approved');
            $query = "INSERT INTO notifications (user_id, message) VALUES (?, 'Your account has been approved.')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            return true;
        }
        return false;
    }

    public function rejectUser($userId) {
        global $conn;
        $query = "UPDATE users SET status = 'rejected' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $query = "SELECT email, name FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            sendApprovalNotice($user['email'], $user['name'], 'rejected');
            $query = "INSERT INTO notifications (user_id, message) VALUES (?, 'Your account has been rejected.')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            return true;
        }
        return false;
    }

    public function updateRole($userId, $newRole) {
        global $conn;
        $query = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $newRole, $userId);
        return $stmt->execute();
    }

    public function getAllUsers() {
        global $conn;
        $query = "SELECT id, name, email, role, status FROM users";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteUser($userId) {
        global $conn;
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
?>