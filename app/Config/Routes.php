<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// Auth Routes
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard');

// Announcements Routes
$routes->get('/announcements', 'Announcement::index');

// Role-specific Registration Routes
$routes->get('/register/admin', 'AdminRegistration::register');
$routes->post('/register/admin', 'AdminRegistration::register');
$routes->get('/register/teacher', 'TeacherRegistration::register');
$routes->post('/register/teacher', 'TeacherRegistration::register');
$routes->get('/register/student', 'StudentRegistration::register');
$routes->post('/register/student', 'StudentRegistration::register');

// Admin Dashboard Routes
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'AdminDashboard::index');
    $routes->get('settings', 'AdminDashboard::settings');
    $routes->get('approvals', 'AdminDashboard::pendingApprovals');
    $routes->post('approve-user', 'AdminDashboard::approveUser');
    $routes->post('reject-user', 'AdminDashboard::rejectUser');
    $routes->post('update-user-role', 'AdminDashboard::updateUserRole');
});

// Teacher Dashboard Routes
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
    $routes->get('courses', 'TeacherDashboard::courses');
    $routes->get('students', 'TeacherDashboard::students');
    $routes->get('grades', 'TeacherDashboard::grades');
});

// Student Dashboard Routes
$routes->group('student', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'StudentDashboard::index');
    $routes->get('courses', 'StudentDashboard::courses');
    $routes->get('browse', 'StudentDashboard::browse');
    $routes->get('grades', 'StudentDashboard::grades');
    $routes->get('materials', 'StudentDashboard::materials');
    $routes->post('enroll', 'StudentDashboard::enroll');
});

// Course Routes
$routes->group('course', function($routes) {
    $routes->get('/', 'Course::index');
    $routes->get('view/(:num)', 'Course::view/$1');
    $routes->post('enroll', 'Course::enroll');
    $routes->post('unenroll', 'Course::unenroll');
    $routes->get('details/(:num)', 'Course::getCourseDetails/$1');
    $routes->get('search', 'Course::search');
});

// Materials Routes
$routes->group('materials', function($routes) {
    $routes->get('download/(:num)', 'Materials::download/$1');
    $routes->get('student', 'Materials::studentMaterials');
    $routes->get('course/(:num)', 'Materials::viewCourse/$1');
});

// Admin Materials Routes (protected)
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
    $routes->get('course/(:num)/upload', 'Materials::upload/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
    $routes->get('materials/delete/(:num)', 'Materials::delete/$1');
});

// Temporary direct routes for testing (remove in production)
$routes->get('admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('test/materials', 'Materials::upload/1'); // Simple test route
$routes->get('debug/materials', function() {
    return 'Materials controller exists and routes are working!';
});
$routes->get('test/upload', 'Materials::test');
$routes->get('test/admin', function() {
    return 'Admin dashboard test - this route works!';
});

$routes->setAutoRoute(true);





