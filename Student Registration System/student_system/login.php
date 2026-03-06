<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Handle form submission for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate required fields
    if (!empty($email) && !empty($password)) {
        // Check if user has registered (credentials stored in session)
        if (isset($_SESSION['registered_email']) && isset($_SESSION['registered_password'])) {
            // Validate credentials
            if ($email === $_SESSION['registered_email'] && $password === $_SESSION['registered_password']) {
                // Create session for logged in student
                $_SESSION['loggedin'] = true;
                $_SESSION['student_id'] = $_SESSION['student_id'] ?? time();
$_SESSION['student_name'] = $_SESSION['student_name'] ?? 'Student';
                $_SESSION['student_email'] = $email;
                
                // Redirect to dashboard
                 header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password. Please try again.";
            }
        } else {
            $error = "No registered user found. Please register first.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Course Registration System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Student Login</h1>
        <p class="subtitle">Please enter your credentials</p>
        
        <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
            <div class="success">Registration successful! Please login with your credentials.</div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn full-width">Login</button>
        </form>
        
        <a href="register.php" class="back-link">Don't have an account? Register</a>
    </div>
</body>
</html>
