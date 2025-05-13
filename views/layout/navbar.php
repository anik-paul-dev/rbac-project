<?php if (isLoggedIn() && isApproved()): ?>
    <nav class="nav nav-pills mb-4">
        <?php if (getUserRole() === 'admin'): ?>
            <a class="nav-link" href="dashboard.php?page=manage_users">Manage Users</a>
            <a class="nav-link" href="dashboard.php?page=manage_posts">Manage Posts</a>
        <?php elseif (getUserRole() === 'editor'): ?>
            <a class="nav-link" href="dashboard.php?page=edit_users">Edit Users</a>
            <a class="nav-link" href="dashboard.php?page=manage_posts">Manage Posts</a>
        <?php elseif (getUserRole() === 'contributor' || getUserRole() === 'user'): ?>
            <a class="nav-link" href="dashboard.php?page=add_post">Add Post</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>