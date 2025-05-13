<?php
include dirname(__DIR__, 2) . '/views/layout/header.php';
require_once dirname(__DIR__, 2) . '/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    if ($auth->register($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
        $success = "Registration successful. Awaiting admin approval.";
    } else {
        $error = "Registration failed. Email may already be in use.";
    }
}
?>

<div class="card">
    <div class="card-header">Register</div>
    <div class="card-body">
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="contributor">Contributor</option>
                    <option value="editor">Editor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</div>

<?php include dirname(__DIR__, 2) . '/views/layout/footer.php'; ?>