<?php
// ============================================
// LOGOUT - Clear login session, cookies and redirect
// ============================================

// Start session
session_start();

// Preserve registration credentials for re-login (save to temporary variables)
$registered_email = $_SESSION['registered_email'] ?? '';
$registered_password = $_SESSION['registered_password'] ?? '';

// Clear all session data except registration credentials
unset($_SESSION['loggedin']);
unset($_SESSION['student_id']);
unset($_SESSION['student_name']);
unset($_SESSION['student_email']);
unset($_SESSION['registered_courses']);
unset($_SESSION['total_units']);
unset($_SESSION['passport']);

// Delete cookies
if (isset($_COOKIE)) {
    foreach ($_COOKIE as $name => $value) {
        // Set cookie with past expiration to delete it
        setcookie($name, '', time() - 3600, '/');
    }
}

// Redirect to login.php
header("Location: login.php");
exit;
?>
