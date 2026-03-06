<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

// Handle form submission for registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $student_id = trim($_POST['student_id'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate required fields
    if (!empty($student_id) && !empty($fullname) && !empty($email) && !empty($password)) {
        // Check if passwords match
        if ($password === $confirm_password) {
            // Store user credentials in session for login validation
            $_SESSION['registered_email'] = htmlspecialchars($email);
            $_SESSION['registered_password'] = $password; // In production, use password_hash()
            
            // Store user data in session (use student-provided ID)
            $_SESSION['student_id'] = htmlspecialchars($student_id);
            $_SESSION['student_name'] = htmlspecialchars($fullname);
            $_SESSION['student_email'] = htmlspecialchars($email);
            $_SESSION['registered_courses'] = [];
            $_SESSION['total_units'] = 0;
            $_SESSION['passport'] = '';
            
            // Redirect to login page after successful registration
            header("Location: login.php?registered=1");
            exit;
        } else {
            $error = "Passwords do not match.";
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
    <title>Register - Student Course Registration System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Student Registration</h1>
        <p class="subtitle">Create your account</p>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="student_id">Student ID *</label>
                <input type="text" id="student_id" name="student_id" placeholder="Enter your Student ID" required>
            </div>
            
            <div class="form-group">
                <label for="fullname">Full Name *</label>
                <input type="text" id="fullname" name="fullname" placeholder="Enter your Full Name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" placeholder="Enter your Email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" placeholder="Enter your Password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your Password" required>
            </div>
            
            <button type="submit" class="btn full-width">Register</button>
        </form>
        
        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</body>
</html>
