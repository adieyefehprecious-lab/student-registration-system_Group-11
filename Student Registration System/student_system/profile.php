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
$student_email = $_SESSION['student_email'] ?? '';
$registered_courses = $_SESSION['registered_courses'] ?? [];
$total_units = $_SESSION['total_units'] ?? 0;
$passport = $_SESSION['passport'] ?? '';

// Course structure with units (for displaying course names)
$courses = [
    "CSC101" => ["name" => "Introduction to Programming", "unit" => 3],
    "MTH103" => ["name" => "Calculus I", "unit" => 2],
    "ICT102" => ["name" => "Web Development", "unit" => 3],
    "ENG101" => ["name" => "Communication Skills", "unit" => 2],
    "STA101" => ["name" => "Statistics I", "unit" => 3]
];

// ============================================
// FILE UPLOAD LOGIC
// ============================================
$uploadMessage = '';
$uploadSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['passport'])) {
    $file = $_FILES['passport'];
    
    // Check if file was uploaded without errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Get file extension
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validate file extension (only jpg, jpeg, png allowed)
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            // Generate unique filename using time() to avoid duplicates
            $newFilename = time() . '_' . uniqid() . '.' . $fileExtension;
            $uploadDir = 'uploads/';
            
            // Create uploads directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $destination = $uploadDir . $newFilename;
            
            // Move uploaded file to uploads folder
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Save new filename in session
                $_SESSION['passport'] = $newFilename;
                $passport = $newFilename;
                $uploadMessage = "Passport uploaded successfully!";
                $uploadSuccess = true;
            } else {
                $uploadMessage = "Error moving uploaded file.";
            }
        } else {
            $uploadMessage = "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
        }
    } else {
        $uploadMessage = "Error uploading file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Student Course Registration System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="has-navbar">
    <!-- Left Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-brand">Student Portal</div>
        <nav class="sidebar-nav">
            <a href="dashboard.php">Dashboard</a>
            <a href="course_register.php">Register Courses</a>
            <a href="profile.php" class="active">Profile</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
    </div>
    
    <div class="main-wrapper">
        <div class="main-content">
            <div class="container wide">
                <h1>Student Profile</h1>
                
                <!-- ============================================ -->
                <!-- PROFILE PICTURE SECTION -->
                <!-- ============================================ -->
                <div class="profile-section">
                    <?php if (!empty($passport)): ?>
                        <img src="uploads/<?php echo htmlspecialchars($passport); ?>" alt="Profile Picture" class="profile-image">
                    <?php elseif (file_exists('uploads/profile1.jpg')): ?>
                        <img src="uploads/profile1.jpg" alt="Default Profile Picture" class="profile-image">
                    <?php else: ?>
                        <svg class="profile-image" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="75" cy="50" r="30" fill="#667eea"/>
                            <path d="M25 130 Q75 80 125 130" fill="#667eea"/>
                        </svg>
                    <?php endif; ?>
                </div>
                
                <!-- ============================================ -->
                <!-- STUDENT INFORMATION -->
                <!-- ============================================ -->
                <div class="info-group">
                    <label>Student ID</label>
                    <p><?php echo htmlspecialchars($student_id); ?></p>
                </div>
                
                <div class="info-group">
                    <label>Full Name</label>
                    <p><?php echo htmlspecialchars($student_name); ?></p>
                </div>
                
                <div class="info-group">
                    <label>Email Address</label>
                    <p><?php echo htmlspecialchars($student_email); ?></p>
                </div>
                
                <!-- ============================================ -->
                <!-- REGISTERED COURSES SECTION -->
                <!-- ============================================ -->
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
                        <div class="total-units">
                            <p>Total Units: <strong><?php echo $total_units; ?></strong></p>
                        </div>
                    <?php else: ?>
                        <p style="color: #999; font-style: italic;">No courses registered yet.</p>
                    <?php endif; ?>
                </div>
                
                <!-- ============================================ -->
                <!-- PASSPORT UPLOAD SECTION -->
                <!-- ============================================ -->
                <div class="upload-section">
                    <h2>Upload Passport Photo</h2>
                    
                    <?php if (!empty($uploadMessage)): ?>
                        <div class="message <?php echo $uploadSuccess ? 'success' : 'error'; ?>">
                            <?php echo htmlspecialchars($uploadMessage); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data" class="upload-form">
                        <div class="file-input-wrapper">
                            <input type="file" name="passport" accept=".jpg,.jpeg,.png" required>
                        </div>
                        <button type="submit" class="btn">Upload Passport</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
