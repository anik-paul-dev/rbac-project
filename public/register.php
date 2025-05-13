<?php
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}
include '../views/auth/register.php';
?>