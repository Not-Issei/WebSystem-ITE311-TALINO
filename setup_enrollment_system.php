<?php

/**
 * Course Enrollment System Setup Script
 * This script sets up the database tables and sample data for the enrollment system
 */

try {
    // Database connection
    $host = 'localhost';
    $dbname = 'lms_talino';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🚀 Setting up Course Enrollment System...\n\n";
    
    // Step 1: Update courses table structure
    echo "📋 Updating courses table structure...\n";
    
    // Check if courses table exists and has proper structure
    $stmt = $pdo->query("SHOW TABLES LIKE 'courses'");
    if ($stmt->rowCount() > 0) {
        // Get current columns
        $stmt = $pdo->query("DESCRIBE courses");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Add missing columns
        if (!in_array('course_code', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN course_code VARCHAR(20) UNIQUE AFTER id");
            echo "✅ Added course_code column\n";
        }
        
        if (!in_array('course_name', $columns)) {
            if (in_array('title', $columns)) {
                $pdo->exec("ALTER TABLE courses CHANGE title course_name VARCHAR(255)");
                echo "✅ Renamed title to course_name\n";
            } else {
                $pdo->exec("ALTER TABLE courses ADD COLUMN course_name VARCHAR(255) AFTER course_code");
                echo "✅ Added course_name column\n";
            }
        }
        
        if (!in_array('teacher_id', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN teacher_id INT(11) UNSIGNED NULL AFTER description");
            echo "✅ Added teacher_id column\n";
        }
        
        if (!in_array('credits', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN credits INT(2) DEFAULT 3 AFTER teacher_id");
            echo "✅ Added credits column\n";
        }
        
        if (!in_array('max_students', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN max_students INT(5) DEFAULT 30 AFTER credits");
            echo "✅ Added max_students column\n";
        }
        
        if (!in_array('semester', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN semester VARCHAR(20) NULL AFTER max_students");
            echo "✅ Added semester column\n";
        }
        
        if (!in_array('academic_year', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN academic_year VARCHAR(10) NULL AFTER semester");
            echo "✅ Added academic_year column\n";
        }
        
        if (!in_array('status', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN status ENUM('active', 'inactive', 'completed') DEFAULT 'active' AFTER academic_year");
            echo "✅ Added status column\n";
        }
        
        if (!in_array('start_date', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN start_date DATE NULL AFTER status");
            echo "✅ Added start_date column\n";
        }
        
        if (!in_array('end_date', $columns)) {
            $pdo->exec("ALTER TABLE courses ADD COLUMN end_date DATE NULL AFTER start_date");
            echo "✅ Added end_date column\n";
        }
    } else {
        // Create courses table from scratch
        $pdo->exec("
            CREATE TABLE courses (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                course_code VARCHAR(20) NOT NULL UNIQUE,
                course_name VARCHAR(255) NOT NULL,
                description TEXT,
                teacher_id INT(11) UNSIGNED NULL,
                credits INT(2) DEFAULT 3,
                max_students INT(5) DEFAULT 30,
                semester VARCHAR(20) NULL,
                academic_year VARCHAR(10) NULL,
                status ENUM('active', 'inactive', 'completed') DEFAULT 'active',
                start_date DATE NULL,
                end_date DATE NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        echo "✅ Created courses table\n";
    }
    
    // Step 2: Update enrollments table structure
    echo "\n📋 Updating enrollments table structure...\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'enrollments'");
    if ($stmt->rowCount() > 0) {
        // Get current columns
        $stmt = $pdo->query("DESCRIBE enrollments");
        $enrollColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (!in_array('enrollment_date', $enrollColumns)) {
            if (in_array('enrolled_at', $enrollColumns)) {
                $pdo->exec("ALTER TABLE enrollments CHANGE enrolled_at enrollment_date DATETIME");
                echo "✅ Renamed enrolled_at to enrollment_date\n";
            } else {
                $pdo->exec("ALTER TABLE enrollments ADD COLUMN enrollment_date DATETIME AFTER course_id");
                echo "✅ Added enrollment_date column\n";
            }
        }
        
        if (!in_array('status', $enrollColumns)) {
            $pdo->exec("ALTER TABLE enrollments ADD COLUMN status ENUM('active', 'completed', 'dropped') DEFAULT 'active' AFTER enrollment_date");
            echo "✅ Added status column to enrollments\n";
        }
        
        if (!in_array('created_at', $enrollColumns)) {
            $pdo->exec("ALTER TABLE enrollments ADD COLUMN created_at DATETIME NULL AFTER status");
            echo "✅ Added created_at column to enrollments\n";
        }
        
        if (!in_array('updated_at', $enrollColumns)) {
            $pdo->exec("ALTER TABLE enrollments ADD COLUMN updated_at DATETIME NULL AFTER created_at");
            echo "✅ Added updated_at column to enrollments\n";
        }
        
        // Add unique constraint if it doesn't exist
        try {
            $pdo->exec("ALTER TABLE enrollments ADD UNIQUE KEY unique_enrollment (user_id, course_id)");
            echo "✅ Added unique constraint to enrollments\n";
        } catch (Exception $e) {
            echo "⚠️  Unique constraint already exists\n";
        }
    } else {
        // Create enrollments table from scratch
        $pdo->exec("
            CREATE TABLE enrollments (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT(11) UNSIGNED NOT NULL,
                course_id INT(11) UNSIGNED NOT NULL,
                enrollment_date DATETIME NOT NULL,
                status ENUM('active', 'completed', 'dropped') DEFAULT 'active',
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                UNIQUE KEY unique_enrollment (user_id, course_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        echo "✅ Created enrollments table\n";
    }
    
    // Step 3: Insert sample courses
    echo "\n📚 Adding sample courses...\n";
    
    // Get a teacher user ID (or use NULL)
    $stmt = $pdo->query("SELECT id FROM users WHERE role = 'teacher' LIMIT 1");
    $teacherUser = $stmt->fetch(PDO::FETCH_ASSOC);
    $teacherId = $teacherUser ? $teacherUser['id'] : null;
    
    $sampleCourses = [
        [
            'course_code' => 'ITE311',
            'course_name' => 'Web Systems and Technologies',
            'description' => 'Learn modern web development technologies including HTML5, CSS3, JavaScript, PHP, and database integration.',
            'teacher_id' => $teacherId,
            'credits' => 3,
            'max_students' => 35,
            'semester' => 'First Semester',
            'academic_year' => '2024-2025',
            'status' => 'active',
            'start_date' => '2024-08-15',
            'end_date' => '2024-12-15',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'course_code' => 'ITE312',
            'course_name' => 'Database Management Systems',
            'description' => 'Comprehensive study of database design, SQL, normalization, and database administration.',
            'teacher_id' => $teacherId,
            'credits' => 3,
            'max_students' => 30,
            'semester' => 'First Semester',
            'academic_year' => '2024-2025',
            'status' => 'active',
            'start_date' => '2024-08-15',
            'end_date' => '2024-12-15',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'course_code' => 'ITE313',
            'course_name' => 'Systems Analysis and Design',
            'description' => 'Learn system development methodologies, UML modeling, and software engineering principles.',
            'teacher_id' => $teacherId,
            'credits' => 3,
            'max_students' => 25,
            'semester' => 'First Semester',
            'academic_year' => '2024-2025',
            'status' => 'active',
            'start_date' => '2024-08-15',
            'end_date' => '2024-12-15',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'course_code' => 'CS101',
            'course_name' => 'Introduction to Programming',
            'description' => 'Fundamentals of programming using Python. Learn variables, control structures, functions, and basic algorithms.',
            'teacher_id' => $teacherId,
            'credits' => 3,
            'max_students' => 40,
            'semester' => 'First Semester',
            'academic_year' => '2024-2025',
            'status' => 'active',
            'start_date' => '2024-08-15',
            'end_date' => '2024-12-15',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'course_code' => 'CS201',
            'course_name' => 'Data Structures and Algorithms',
            'description' => 'Study of fundamental data structures and algorithms including arrays, linked lists, trees, and sorting algorithms.',
            'teacher_id' => $teacherId,
            'credits' => 4,
            'max_students' => 30,
            'semester' => 'Second Semester',
            'academic_year' => '2024-2025',
            'status' => 'active',
            'start_date' => '2025-01-15',
            'end_date' => '2025-05-15',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]
    ];
    
    $insertStmt = $pdo->prepare("
        INSERT IGNORE INTO courses 
        (course_code, course_name, description, teacher_id, credits, max_students, semester, academic_year, status, start_date, end_date, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($sampleCourses as $course) {
        $insertStmt->execute([
            $course['course_code'],
            $course['course_name'],
            $course['description'],
            $course['teacher_id'],
            $course['credits'],
            $course['max_students'],
            $course['semester'],
            $course['academic_year'],
            $course['status'],
            $course['start_date'],
            $course['end_date'],
            $course['created_at'],
            $course['updated_at']
        ]);
        
        if ($insertStmt->rowCount() > 0) {
            echo "✅ Added course: {$course['course_code']} - {$course['course_name']}\n";
        } else {
            echo "⚠️  Course {$course['course_code']} already exists\n";
        }
    }
    
    // Step 4: Create test student if none exists
    echo "\n👤 Creating test student...\n";
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(['test@student.com']);
    
    if ($stmt->rowCount() == 0) {
        $studentId = 'STU' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $pdo->prepare("
            INSERT INTO users 
            (name, email, password, role, student_id, department, phone, address, status, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ")->execute([
            'Test Student',
            'test@student.com',
            password_hash('test123', PASSWORD_DEFAULT),
            'student',
            $studentId,
            'Computer Science',
            '09123456789',
            '123 Test Street, Test City',
            'active',
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        
        echo "✅ Test student created (test@student.com / test123)\n";
    } else {
        echo "✅ Test student already exists\n";
    }
    
    echo "\n🎉 Course Enrollment System setup completed successfully!\n\n";
    
    echo "📋 Summary:\n";
    echo "   • Courses table updated with proper structure\n";
    echo "   • Enrollments table updated with proper structure\n";
    echo "   • " . count($sampleCourses) . " sample courses added\n";
    echo "   • Test student account ready\n";
    echo "   • AJAX enrollment system ready\n\n";
    
    echo "🧪 Testing Instructions:\n";
    echo "   1. Login as student: test@student.com / test123\n";
    echo "   2. Go to /student/dashboard\n";
    echo "   3. Click 'Enroll Now' on any available course\n";
    echo "   4. Verify enrollment without page reload\n";
    echo "   5. Check enrolled courses section\n\n";
    
    echo "🚀 System is ready for testing!\n";
    
} catch (Exception $e) {
    echo "❌ Setup failed: " . $e->getMessage() . "\n";
    echo "💡 Make sure XAMPP MySQL is running and database 'lms_talino' exists\n";
}
?>
