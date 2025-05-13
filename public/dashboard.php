<?php
require_once '../middleware/auth.php';
require_once '../controllers/BlogController.php';
restrictAccess();

$role = getUserRole();
$dashboardFile = "../views/dashboard/$role.php";

include '../views/layout/header.php';

if (file_exists($dashboardFile)) {
    include $dashboardFile;
} else {
    echo "Dashboard not found.";
}

include '../views/layout/footer.php';
?>