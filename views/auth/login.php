<?php
include dirname(__DIR__, 2) . '/views/layout/header.php';
require_once dirname(__DIR__, 2) . '/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $result = $auth->login($_POST['email'], $_POST['password']);
    if ($result['success']) {
        header("Location: " . dirname(__DIR__, 2) . "/public/dashboard.php");
        exit;
    } else {
        $error = $result['message'];
        // Debug output
        $email = $_POST['email'];
        error_log("Login failed for email: $email, Message: $error");
    }
}
?>

<div class="card">
    <div class="card-header">Login</div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="admin@example.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="admin123" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>

<?php include dirname(__DIR__, 2) . '/views/layout/footer.php'; ?>