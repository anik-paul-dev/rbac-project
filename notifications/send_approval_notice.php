<?php
function sendApprovalNotice($email, $name, $status) {
    $subject = "Account $status";
    $message = "Dear $name, your account has been $status by the admin.";
    $headers = "From: no-reply@rbacsystem.com";
    mail($email, $subject, $message, $headers);
}
?>