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

// Course structure with units
$courses = [
    "CSC101" => ["name" => "Introduction to Programming", "unit" => 3],
    "MTH103" => ["name" => "Calculus I", "unit" => 2],
    "ICT102" => ["name" => "Web Development", "unit" => 3],
    "ENG101" => ["name" => "Communication Skills", "unit" => 2],
    "STA101" => ["name" => "Statistics I", "unit" => 3]
];

// Maximum allowed units
$max_units = 18;

// Handle course registration form submission
$message = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['courses'])) {
    $selected_courses = $_POST['courses'];
    
    if (!empty($selected_courses)) {
        // Calculate total units for selected courses
        $total_units = 0;
        foreach ($selected_courses as $course_code) {
            if (isset($courses[$course_code])) {
                $total_units += $courses[$course_code]['unit'];
            }
        }
        
        // Get current total units from existing registered courses
        $current_total_units = $_SESSION['total_units'] ?? 0;
        
        // Calculate new total units
        $new_total_units = $current_total_units + $total_units;
        
        // Check if exceeds maximum unit limit
        if ($new_total_units > $max_units) {
            $message = "Cannot register courses. Total units would exceed the maximum limit of $max_units units.";
            $success = false;
        } else {
            // Merge new courses with existing ones (avoiding duplicates)
            $new_courses = array_merge($registered_courses, $selected_courses);
            $registered_courses = array_unique($new_courses);
            
            // Save to session
            $_SESSION['registered_courses'] = $registered_courses;
            $_SESSION['total_units'] = $new_total_units;
            
            // Redirect to confirmation page
            header("Location: confirmation.php");
            exit;
        }
    } else {
        $message = "Please select at least one course.";
    }
}

// Calculate current total units for display
$current_total_units = $_SESSION['total_units'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration - Student Course Registration System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="has-navbar">
    <!-- Left Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-brand">Student Portal</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="course_register.php" class="active">Register Courses</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
    </div>
    
    <div class="main-wrapper">
        <div class="main-content">
            <div class="container wider">
                <h1>Course Registration</h1>
                <p class="subtitle">Welcome, <?php echo htmlspecialchars($student_name); ?>! Select courses to register.</p>
                
                <?php if (!empty($message)): ?>
                    <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Display currently registered courses -->
                <?php if (!empty($registered_courses)): ?>
                    <div style="margin-bottom: 20px;">
                        <strong>Currently Registered:</strong>
                        <ul style="margin-top: 10px; padding-left: 20px;">
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
                    </div>
                <?php endif; ?>
                
                <!-- Course registration form -->
                <form method="POST" action="">
                    <div class="courses-grid">
                        <?php foreach ($courses as $course_code => $course_info): ?>
                            <div class="checkbox-item">
                                <input type="checkbox" id="course_<?php echo $course_code; ?>" 
                                       name="courses[]" value="<?php echo htmlspecialchars($course_code); ?>"
                                       <?php echo in_array($course_code, $registered_courses) ? 'checked' : ''; ?>>
                                <label for="course_<?php echo $course_code; ?>">
                                    <?php echo htmlspecialchars($course_code . ' - ' . $course_info['name'] . ' (' . $course_info['unit'] . ' units)'); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="unit-info">
                        <p>Current Total Units: <strong><?php echo $current_total_units; ?></strong> / <?php echo $max_units; ?></p>
                    </div>
                    
                    <button type="submit" class="btn">Register Selected Courses</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
