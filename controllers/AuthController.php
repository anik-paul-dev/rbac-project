<?php
require_once '../config/db.php';
require_once '../middleware/auth.php';
require_once '../notifications/send_registration_alert.php';

class AuthController {
    public function register($name, $email, $password, $role) {
        global $conn;
        $role = in_array($role, ['editor', 'contributor', 'user']) ? $role : 'user';
        $password = password_hash($password, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        
        if ($stmt->execute()) {
            $userId = $conn->insert_id;
            sendRegistrationAlert($name, $email);
            $query = "INSERT INTO notifications (user_id, message) VALUES (?, 'Your account is pending approval.')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            return true;
        }
        return false;
    }

    public function login($email, $password) {
        global $conn;
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                if ($user['status'] === 'rejected') {
                    return ['success' => false, 'message' => 'Account rejected.'];
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_status'] = $user['status'];
                return [
                    'success' => true,
                    'token' => generateJWT($user['id'])
                ];
            }
        }
        return ['success' => false, 'message' => 'Invalid credentials.'];
    }

    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>