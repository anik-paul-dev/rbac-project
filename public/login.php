<?php
require_once '../middleware/auth.php';
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}
include '../views/auth/login.php';
?>