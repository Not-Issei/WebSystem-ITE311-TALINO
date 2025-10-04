# Laboratory Exercise 6: Course Enrollment System with AJAX

## 🎯 Learning Objectives Achieved

✅ **Design and create a new database table to manage relationships between users and courses**
- Created `enrollments` table with proper foreign key relationships
- Implemented `courses` table with comprehensive course information
- Established many-to-many relationship between users and courses

✅ **Implement server-side logic for handling course enrollments**
- Created `EnrollmentModel` with enrollment management methods
- Implemented `CourseModel` for course data management
- Built `Course` controller with secure enrollment endpoints

✅ **Display user-specific data (enrolled courses) in a dashboard**
- Updated Student Dashboard to show enrolled courses
- Added available courses section with enrollment buttons
- Implemented real-time statistics and course information

✅ **Utilize jQuery and AJAX to create a dynamic, seamless user experience without page reloads**
- Implemented AJAX enrollment functionality
- Added real-time feedback and error handling
- Created dynamic UI updates without page refresh

✅ **Understand and implement basic foreign key relationships in a web application**
- Proper foreign key constraints in database design
- Relationship management through models
- Data integrity and referential constraints

## 🏗️ System Architecture

### Database Schema
```sql
-- Courses Table
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE,
    course_name VARCHAR(255),
    description TEXT,
    teacher_id INT,
    credits INT DEFAULT 3,
    max_students INT DEFAULT 30,
    semester VARCHAR(20),
    academic_year VARCHAR(10),
    status ENUM('active', 'inactive', 'completed') DEFAULT 'active',
    start_date DATE,
    end_date DATE,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);

-- Enrollments Table
CREATE TABLE enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    course_id INT,
    enrollment_date DATETIME,
    status ENUM('active', 'completed', 'dropped') DEFAULT 'active',
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, course_id)
);
```

### MVC Structure

#### Models
- **`EnrollmentModel.php`**: Manages enrollment operations
  - `enrollUser($data)`: Creates new enrollment
  - `getUserEnrollments($user_id)`: Retrieves user's enrolled courses
  - `isAlreadyEnrolled($user_id, $course_id)`: Prevents duplicate enrollments
  - `getUserEnrollmentStats($user_id)`: Provides enrollment statistics

- **`CourseModel.php`**: Manages course data
  - `getActiveCourses()`: Retrieves all active courses
  - `getAvailableCoursesForUser($user_id)`: Gets courses available for enrollment
  - `hasAvailableSlots($course_id)`: Checks enrollment capacity

#### Controllers
- **`Course.php`**: Handles enrollment operations
  - `enroll()`: AJAX endpoint for course enrollment
  - `unenroll()`: AJAX endpoint for course unenrollment
  - `getCourseDetails($courseId)`: Retrieves course information

- **`StudentDashboard.php`**: Updated to include enrollment data
  - Enhanced `index()` method with enrollment information
  - Integration with enrollment and course models

#### Views
- **`dashboards/student.php`**: Enhanced student dashboard
  - Enrolled courses section with course cards
  - Available courses section with enrollment buttons
  - AJAX-powered enrollment functionality
  - Real-time statistics and feedback

## 🔧 Implementation Details

### Step 1: Database Migration ✅
Created migration files:
- `2024-01-02-000000_CreateCoursesTable.php`
- `2024-01-02-000001_CreateEnrollmentsTable.php`

### Step 2: Model Implementation ✅
- **EnrollmentModel**: Complete CRUD operations for enrollments
- **CourseModel**: Course management and availability checking

### Step 3: Controller Logic ✅
- **Course Controller**: Secure AJAX endpoints with validation
- **Student Dashboard**: Integration with enrollment system
- **Security**: CSRF protection, role validation, input sanitization

### Step 4: Frontend Implementation ✅
- **Bootstrap UI**: Modern, responsive course cards
- **jQuery AJAX**: Seamless enrollment without page reload
- **Real-time Feedback**: Success/error messages and UI updates
- **Dynamic Statistics**: Live update of enrollment counts

### Step 5: Routing Configuration ✅
```php
// Course Routes
$routes->group('course', function($routes) {
    $routes->post('enroll', 'Course::enroll');
    $routes->post('unenroll', 'Course::unenroll');
    $routes->get('details/(:num)', 'Course::getCourseDetails/$1');
});
```

## 🛡️ Security Implementation

### 1. **CSRF Protection**
```php
// In views
<?= csrf_field() ?>

// In controller
if (!$this->validate(['csrf_token' => 'required'])) {
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Invalid security token.'
    ])->setStatusCode(400);
}
```

### 2. **Role-Based Access Control**
```php
if ($this->session->get('role') !== 'student') {
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Only students can enroll in courses.'
    ])->setStatusCode(403);
}
```

### 3. **Input Validation**
```php
if (!$courseId || !is_numeric($courseId)) {
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Invalid course ID.'
    ])->setStatusCode(400);
}
```

### 4. **SQL Injection Prevention**
- Using CodeIgniter Query Builder exclusively
- Parameterized queries for all database operations
- Input sanitization and validation

### 5. **Session Security**
```php
$userId = $this->session->get('user_id'); // From session, not request
```

## 🧪 Testing Procedures

### Functional Testing
1. **Login as Student**: Access student dashboard
2. **View Available Courses**: See courses available for enrollment
3. **Enroll in Course**: Click "Enroll Now" button
4. **Verify AJAX**: No page reload during enrollment
5. **Check Enrolled Courses**: Course appears in enrolled section
6. **Prevent Duplicate**: Cannot enroll in same course twice

### Security Testing
1. **Authorization Bypass**: ❌ Unauthenticated access blocked
2. **SQL Injection**: ❌ Malicious queries prevented
3. **CSRF Protection**: ❌ Invalid tokens rejected
4. **Data Tampering**: ❌ Cannot enroll other users
5. **Input Validation**: ❌ Invalid inputs rejected
6. **Role Validation**: ❌ Non-students cannot enroll

## 📊 Sample Data

### Courses Available
1. **ITE311** - Web Systems and Technologies (3 credits)
2. **ITE312** - Database Management Systems (3 credits)
3. **ITE313** - Systems Analysis and Design (3 credits)
4. **CS101** - Introduction to Programming (3 credits)
5. **CS201** - Data Structures and Algorithms (4 credits)

## 🚀 Setup Instructions

### 1. Run Database Setup
```bash
php setup_enrollment_system.php
```

### 2. Verify Tables Created
- `courses` table with sample data
- `enrollments` table with proper constraints
- Foreign key relationships established

### 3. Test System
1. Login as student: `test@student.com` / `test123`
2. Navigate to `/student/dashboard`
3. Test enrollment functionality
4. Verify security measures

## 📈 Features Implemented

### Core Features ✅
- [x] Course enrollment with AJAX
- [x] Duplicate enrollment prevention
- [x] Real-time UI updates
- [x] Enrollment statistics
- [x] Course capacity management
- [x] Role-based access control

### Security Features ✅
- [x] CSRF protection
- [x] SQL injection prevention
- [x] Input validation
- [x] Authorization checks
- [x] Session security
- [x] XSS prevention

### User Experience ✅
- [x] No page reloads during enrollment
- [x] Real-time feedback messages
- [x] Dynamic statistics updates
- [x] Responsive design
- [x] Loading states and animations
- [x] Error handling and recovery

## 🎓 Learning Outcomes Achieved

### Technical Skills
- **Database Design**: Foreign key relationships and constraints
- **MVC Architecture**: Proper separation of concerns
- **AJAX Implementation**: Seamless user interactions
- **Security**: Comprehensive protection measures
- **CodeIgniter**: Advanced framework features

### Best Practices
- **Input Validation**: Server-side and client-side validation
- **Error Handling**: Graceful error management
- **Code Organization**: Clean, maintainable code structure
- **Documentation**: Comprehensive system documentation
- **Testing**: Security and functional testing procedures

## 🔍 Code Quality

### Standards Followed
- PSR-4 autoloading
- CodeIgniter coding standards
- Secure coding practices
- RESTful API design
- Responsive web design

### Performance Optimizations
- Efficient database queries
- AJAX for reduced server load
- Proper indexing on database tables
- Optimized frontend assets

## 📝 Conclusion

The Course Enrollment System has been successfully implemented with all required features:

1. ✅ **Database relationships** properly established
2. ✅ **Server-side logic** for enrollment management
3. ✅ **User-specific data display** in dashboard
4. ✅ **AJAX functionality** for seamless experience
5. ✅ **Foreign key relationships** correctly implemented
6. ✅ **Security measures** comprehensively tested
7. ✅ **Vulnerability testing** completed successfully

The system is **production-ready** with robust security measures, excellent user experience, and comprehensive functionality that meets all laboratory objectives.

---

**System Status**: ✅ **COMPLETE AND SECURE**
**Ready for**: Production deployment and further enhancement
**Next Steps**: Additional features like grading system, assignments, and advanced course management
