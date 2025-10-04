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

// Debug and utility routes
$routes->get('/fix-users', 'FixUsers::index');
$routes->get('/debug-session', function() {
    $session = \Config\Services::session();
    echo '<h2>Session Debug Info</h2>';
    echo '<pre>';
    var_dump($session->get());
    echo '</pre>';
    echo '<p><a href="/login">Go to Login</a></p>';
});
$routes->get('/test-student', 'TestController::student');
$routes->get('/emergency-logout', 'Emergency::logout');
$routes->get('/emergency-reset', 'Emergency::clearAll');

// Role-based Dashboard Routes
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'AdminDashboard::index');
    $routes->get('users', 'AdminDashboard::users');
    $routes->get('settings', 'AdminDashboard::settings');
    $routes->post('update-user-role', 'AdminDashboard::updateUserRole');
});

$routes->group('teacher', function($routes) {
    $routes->get('dashboard', 'TeacherDashboard::index');
    $routes->get('courses', 'TeacherDashboard::courses');
    $routes->get('students', 'TeacherDashboard::students');
    $routes->get('grades', 'TeacherDashboard::grades');
});

$routes->group('student', function($routes) {
    $routes->get('dashboard', 'StudentDashboard::index');
    $routes->get('courses', 'StudentDashboard::courses');
    $routes->get('browse', 'StudentDashboard::browse');
    $routes->get('grades', 'StudentDashboard::grades');
    $routes->post('enroll', 'StudentDashboard::enroll');
});

$routes->setAutoRoute(true);





