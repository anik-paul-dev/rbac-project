<?php
function sendRegistrationAlert($name, $email) {
    $adminEmail = 'admin@example.com';
    $subject = "New User Registration";
    $message = "A new user ($name, $email) has registered and is awaiting approval.";
    $headers = "From: no-reply@rbacsystem.com";
    mail($adminEmail, $subject, $message, $headers);
}
?>