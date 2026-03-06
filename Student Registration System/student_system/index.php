<?php
// ============================================
// INDEX PAGE - Welcome/Landing page
// ============================================
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSTU Student Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Welcome heading -->
        <h1 class="center">Welcome to KSTU Student Portal</h1>
        
        <!-- Subtitle -->
        <p class="subtitle">Empowering Students Through Technology</p>
        
        <!-- Main CTA Button -->
        <div class="links">
            <a href="login.php" class="btn">Enter Portal</a>
        </div>
    </div>
</body>
</html>
