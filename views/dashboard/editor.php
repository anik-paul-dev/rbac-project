<?php
require_once '../../controllers/BlogController.php';
restrictAccess('editor');

$blogController = new BlogController();
$posts = $blogController->getPosts();

$page = isset($_GET['page']) ? $_GET['page'] : 'manage_posts';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'manage_posts' && isset($_POST['delete_post'])) {
    $blogController->deletePost($_POST['post_id']);
    header("Location: dashboard.php?page=manage_posts");
    exit;
}
?>

<?php include '../layout/navbar.php'; ?>

<?php if ($page === 'manage_posts'): ?>
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
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit" name="delete_post" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php elseif ($page === 'edit_users'): ?>
    <h2>Edit Users</h2>
    <p>User editing functionality can be implemented here.</p>
<?php endif; ?>