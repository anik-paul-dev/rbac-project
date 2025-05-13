<?php
$basePath = dirname(__DIR__, 2);
$userControllerPath = $basePath . '/controllers/UserController.php';
$blogControllerPath = $basePath . '/controllers/BlogController.php';
$authPath = $basePath . '/middleware/auth.php';

// Debug file existence
if (!file_exists($userControllerPath)) {
    error_log("UserController.php not found at: $userControllerPath");
    die("Error: UserController.php not found.");
}
if (!file_exists($blogControllerPath)) {
    error_log("BlogController.php not found at: $blogControllerPath");
    die("Error: BlogController.php not found.");
}
if (!file_exists($authPath)) {
    error_log("auth.php not found at: $authPath");
    die("Error: auth.php not found.");
}

require_once $userControllerPath;
require_once $blogControllerPath;
require_once $authPath;
restrictAccess('admin');

$userController = new UserController();
$blogController = new BlogController();
$search = isset($_GET['search']) ? $_GET['search'] : '';
$roleFilter = isset($_GET['role']) ? $_GET['role'] : '';
$pendingUsers = $userController->getPendingUsers($search, $roleFilter);
$posts = $blogController->getPosts();
$allUsers = $userController->getAllUsers();

$page = isset($_GET['page']) ? $_GET['page'] : 'manage_users';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'manage_users') {
    if (isset($_POST['update_role'])) {
        $userController->updateRole($_POST['user_id'], $_POST['new_role']);
    } elseif (isset($_POST['delete_user'])) {
        $userController->deleteUser($_POST['user_id']);
    }
}
?>

<?php include $basePath . '/views/layout/navbar.php'; ?>

<?php if ($page === 'manage_users'): ?>
    <h2>Manage Users</h2>
    <form method="GET" class="mb-4">
        <input type="hidden" name="page" value="manage_users">
        <div class="row">
            <div class="col-md-6 mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4 mb-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="editor" <?php echo $roleFilter === 'editor' ? 'selected' : ''; ?>>Editor</option>
                    <option value="contributor" <?php echo $roleFilter === 'contributor' ? 'selected' : ''; ?>>Contributor</option>
                    <option value="user" <?php echo $roleFilter === 'user' ? 'selected' : ''; ?>>User</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>
    <h3>Pending Approvals</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="pending-users">
            <?php foreach ($pendingUsers as $user): ?>
                <tr data-user-id="<?php echo $user['id']; ?>">
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <button class="btn btn-success btn-sm approve-user" data-id="<?php echo $user['id']; ?>">Approve</button>
                        <button class="btn btn-danger btn-sm reject-user" data-id="<?php echo $user['id']; ?>">Reject</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>All Users</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allUsers as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <select name="new_role" onchange="this.form.submit()">
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="editor" <?php echo $user['role'] === 'editor' ? 'selected' : ''; ?>>Editor</option>
                                <option value="contributor" <?php echo $user['role'] === 'contributor' ? 'selected' : ''; ?>>Contributor</option>
                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                            </select>
                            <input type="hidden" name="update_role" value="1">
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($user['status']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($page === 'manage_posts'): ?>
    <h2>Manage Posts</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td><?php echo htmlspecialchars(substr($post['content'], 0, 50)); ?>...</td>
                    <td><?php echo $post['created_at']; ?></td>
                    <td>
                        <form method="POST" action="dashboard.php?page=manage_posts" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit" name="delete_post" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>