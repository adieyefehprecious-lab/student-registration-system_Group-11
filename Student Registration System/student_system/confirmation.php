<?php
// ============================================
// SESSION CHECK - Protect this page
// ============================================
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to index if not logged in
    header("Location: index.php");
    exit;
}

// Get student data from session
$student_id = $_SESSION['student_id'] ?? '';
$student_name = $_SESSION['student_name'] ?? 'Student';
$registered_courses = $_SESSION['registered_courses'] ?? [];
$total_units = $_SESSION['total_units'] ?? 0;

// Course structure with units (for displaying course names)
$courses = [
    "CSC101" => ["name" => "Introduction to Programming", "unit" => 3],
    "MTH103" => ["name" => "Calculus I", "unit" => 2],
    "ICT102" => ["name" => "Web Development", "unit" => 3],
    "ENG101" => ["name" => "Communication Skills", "unit" => 2],
    "STA101" => ["name" => "Statistics I", "unit" => 3]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed - Student Course Registration System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="has-navbar">
    <!-- Left Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-brand">Student Portal</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="course_register.php">Register Courses</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
    </div>
    
    <div class="main-wrapper">
        <div class="main-content">
            <div class="container wide">
                <!-- Success Message -->
                <div class="success-message">
                    <h1>Registration Successful!</h1>
                    <p>Your courses have been registered successfully.</p>
                </div>
                
                <!-- Student Info -->
                <div class="student-info">
                    <p><strong>Student Name:</strong> <?php echo htmlspecialchars($student_name); ?></p>
                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_id); ?></p>
                </div>
                
                <!-- Registered Courses with Units -->
                <div class="courses-section">
                    <h2>Registered Courses</h2>
                    <?php if (!empty($registered_courses)): ?>
                        <ul class="course-list">
                            <?php foreach ($registered_courses as $course_code): ?>
                                <li>
                                    <?php 
                                    if (isset($courses[$course_code])) {
                                        echo htmlspecialchars($course_code . ' - ' . $courses[$course_code]['name'] . ' (' . $courses[$course_code]['unit'] . ' units)');
                                    } else {
                                        echo htmlspecialchars($course_code);
                                    }
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="no-courses">No courses registered yet.</p>
                    <?php endif; ?>
                </div>
                
                <!-- Total Units -->
                <div class="total-units">
                    <p>Total Units: <strong><?php echo $total_units; ?></strong></p>
                </div>
                
                <!-- Navigation links -->
                <div class="links">
                    <a href="dashboard.php" class="btn btn-success">Back to Dashboard</a>
                    <a href="course_register.php" class="btn">Register More Courses</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
