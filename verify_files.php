<?php
$basePath = dirname(__DIR__);
$filesToCheck = [
    'controllers/UserController.php',
    'controllers/BlogController.php',
    'middleware/auth.php',
    'assets/css/style.css',
    'assets/js/scripts.js',
    'config/db.php',
    'public/login.php',
    'views/layout/header.php',
    'views/layout/footer.php',
    'views/dashboard/admin.php',
];

echo "Checking file existence in: $basePath\n";

foreach ($filesToCheck as $file) {
    $fullPath = $basePath . '/' . $file;
    if (file_exists($fullPath)) {
        echo "Found: $file\n";
    } else {
        echo "Missing: $file\n";
        error_log("Missing file: $fullPath");
    }
}
?>


build a complete website basis on this. don't make mistake and provide all the codes sequentially.


php -S localhost:8000 -t public