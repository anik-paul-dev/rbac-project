<?php
require_once '../../controllers/BlogController.php';
restrictAccess('user');

$blogController = new BlogController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post'])) {
    $blogController->addPost($_POST['title'], $_POST['content'], $_SESSION['user_id']);
}
?>

<?php include '../layout/navbar.php'; ?>

<h2>Add Blog Post</h2>
<form method="POST">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea class="form-control" id="content" name="content" required></textarea>
    </div>
    <button type="submit" name="add_post" class="btn btn-primary">Add Post</button>
</form>