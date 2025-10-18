<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - ITE311 TALINO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }
        
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #28a745;
            color: white;
            border-radius: 8px 8px 0 0 !important;
            border-bottom: none;
            padding: 1rem 1.5rem;
        }
        
        .stats-card {
            text-align: center;
            padding: 2rem 1rem;
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-card.courses .stats-icon { color: #007bff; }
        .stats-card.courses .stats-number { color: #007bff; }
        .stats-card.students .stats-icon { color: #28a745; }
        .stats-card.students .stats-number { color: #28a745; }
        .stats-card.quizzes .stats-icon { color: #ffc107; }
        .stats-card.quizzes .stats-number { color: #ffc107; }
        .stats-card.grades .stats-icon { color: #dc3545; }
        .stats-card.grades .stats-number { color: #dc3545; }
        
        .welcome-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .nav-pills .nav-link {
            color: #6c757d;
            border-radius: 6px;
            margin-right: 0.5rem;
        }
        
        .nav-pills .nav-link.active {
            background-color: #28a745;
        }
        
        .teacher-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-chalkboard-teacher me-2"></i>ITE311 TALINO - Teacher Portal
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-tie me-1"></i><?= esc($user['name']) ?>
                        <span class="teacher-badge ms-2">TEACHER</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-12">
                    <h2 class="mb-2">
                        <i class="fas fa-apple-alt me-2"></i>Welcome, Professor <?= esc($user['name']) ?>!
                    </h2>
                    <p class="mb-3 opacity-90">Manage your courses, students, and assessments from your teaching dashboard.</p>
                    <div class="d-flex flex-wrap gap-3 mb-2">
                        <span class="badge bg-white bg-opacity-20 px-3 py-2">
                            <i class="fas fa-envelope me-1"></i><?= esc($user['email']) ?>
                        </span>
                        <span class="badge bg-white bg-opacity-20 px-3 py-2">
                            <i class="fas fa-calendar me-1"></i><?= date('l, F j, Y') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Pills -->
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="<?= base_url('/teacher/dashboard') ?>">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/teacher/courses') ?>">
                    <i class="fas fa-book me-1"></i>My Courses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/teacher/students') ?>">
                    <i class="fas fa-users me-1"></i>Students
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/teacher/grades') ?>">
                    <i class="fas fa-clipboard-check me-1"></i>Grades
                </a>
            </li>
        </ul>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card courses">
                    <div class="stats-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stats-number"><?= $stats['total_courses'] ?></div>
                    <div class="stats-label">My Courses</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card students">
                    <div class="stats-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stats-number"><?= $stats['total_students'] ?></div>
                    <div class="stats-label">Total Students</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card quizzes">
                    <div class="stats-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="stats-number"><?= $stats['total_quizzes'] ?></div>
                    <div class="stats-label">Quizzes Created</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card grades">
                    <div class="stats-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stats-number"><?= $stats['pending_grades'] ?></div>
                    <div class="stats-label">Pending Grades</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="<?= base_url('/teacher/courses') ?>" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2"></i>Create Course
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="<?= base_url('/teacher/grades') ?>" class="btn btn-success w-100">
                                    <i class="fas fa-clipboard-check me-2"></i>Grade Assignments
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="<?= base_url('/teacher/students') ?>" class="btn btn-info w-100">
                                    <i class="fas fa-users me-2"></i>View Students
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-warning w-100">
                                    <i class="fas fa-chart-bar me-2"></i>Analytics
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teaching Overview -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Today's Schedule</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No classes scheduled for today</h5>
                            <p class="text-muted">Your schedule will appear here when you have classes or meetings.</p>
                            <a href="<?= base_url('/teacher/courses') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-plus me-1"></i>Create New Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($stats['pending_grades'] > 0): ?>
                            <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clipboard-list fa-lg text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Pending Grades</h6>
                                    <p class="mb-0 text-muted small"><?= $stats['pending_grades'] ?> submissions need grading</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users fa-lg text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Students Enrolled</h6>
                                <p class="mb-0 text-muted small"><?= $stats['total_students'] ?> students across all courses</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <div class="flex-shrink-0">
                                <i class="fas fa-book fa-lg text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Active Courses</h6>
                                <p class="mb-0 text-muted small"><?= $stats['total_courses'] ?> courses being taught</p>
                            </div>
                        </div>
                        
                        <?php if ($stats['total_courses'] == 0): ?>
                            <div class="text-center mt-3">
                                <small class="text-muted">Start by creating your first course!</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
