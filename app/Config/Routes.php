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
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('settings', 'AdminDashboard::settings');
    $routes->get('approvals', 'AdminDashboard::pendingApprovals');
    $routes->post('approve-user', 'AdminDashboard::approveUser');
    $routes->post('reject-user', 'AdminDashboard::rejectUser');
    $routes->post('update-user-role', 'AdminDashboard::updateUserRole');
});

// Teacher Dashboard Routes
$routes->group('teacher', function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
    $routes->get('courses', 'TeacherDashboard::courses');
    $routes->get('students', 'TeacherDashboard::students');
    $routes->get('grades', 'TeacherDashboard::grades');
});

// Student Dashboard Routes
$routes->group('student', function($routes) {
    $routes->get('dashboard', 'StudentDashboard::index');
    $routes->get('courses', 'StudentDashboard::courses');
    $routes->get('browse', 'StudentDashboard::browse');
    $routes->get('grades', 'StudentDashboard::grades');
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

$routes->setAutoRoute(true);





