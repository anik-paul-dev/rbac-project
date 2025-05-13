<?php
// Password to hash
$password = 'admin123'; // Change to any password you want to hash

// Generate bcrypt hash
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Output the hash
echo "Password: $password\n";
echo "Bcrypt Hash: $hashedPassword\n";

// Verify the password (for testing)
if (password_verify($password, $hashedPassword)) {
    echo "Password verification: Success\n";
} else {
    echo "Password verification: Failed\n";
}
?>


<!-- php generate_password_hash.php -->