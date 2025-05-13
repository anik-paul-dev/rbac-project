<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isApproved() {
    return isset($_SESSION['user_status']) && $_SESSION['user_status'] === 'approved';
}

function getUserRole() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

function restrictAccess($requiredRole = null) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
    if (!isApproved()) {
        header("Location: not_approved.php");
        exit;
    }
    if ($requiredRole && getUserRole() !== $requiredRole) {
        header("Location: dashboard.php");
        exit;
    }
}

function hasPermission($permission) {
    global $conn;
    $role = getUserRole();
    if (!$role) return false;

    $query = "SELECT p.name FROM permissions p
              JOIN role_permissions rp ON p.id = rp.permission_id
              JOIN roles r ON rp.role_id = r.id
              WHERE r.name = ? AND p.name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $role, $permission);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function generateJWT($userId) {
    $env = parse_ini_file('../.env');
    $secret = $env['JWT_SECRET'];
    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode([
        'user_id' => $userId,
        'iat' => time(),
        'exp' => time() + 3600 // 1 hour expiration
    ]));
    $signature = base64_encode(hash_hmac('sha256', "$header.$payload", $secret, true));
    return "$header.$payload.$signature";
}

function verifyJWT($token) {
    $env = parse_ini_file('../.env');
    $secret = $env['JWT_SECRET'];
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;

    $header = $parts[0];
    $payload = $parts[1];
    $signature = $parts[2];

    $validSignature = base64_encode(hash_hmac('sha256', "$header.$payload", $secret, true));
    if ($signature !== $validSignature) return false;

    $payloadData = json_decode(base64_decode($payload), true);
    if ($payloadData['exp'] < time()) return false;

    return $payloadData['user_id'];
}
?>