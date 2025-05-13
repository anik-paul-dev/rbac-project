<?php
require_once dirname(__DIR__, 2) . '/middleware/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RBAC System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/RBAC_project/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="dashboard.php">RBAC System</a>
                <div class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                        <a class="nav-link" href="logout.php">Logout</a>
                    <?php else: ?>
                        <a class="nav-link" href="login.php">Login</a>
                        <a class="nav-link" href="register.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mt-4">
<?php
if (isLoggedIn() && !isApproved()) {
    include dirname(__DIR__, 2) . '/views/partials/approval_notice.php';
}
?>