<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - ITE311 TALINO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
            background-color: #007bff;
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
        .stats-card.assignments .stats-icon { color: #dc3545; }
        .stats-card.assignments .stats-number { color: #dc3545; }
        .stats-card.pending .stats-icon { color: #ffc107; }
        .stats-card.pending .stats-number { color: #ffc107; }
        .stats-card.grade .stats-icon { color: #28a745; }
        .stats-card.grade .stats-number { color: #28a745; }
        
        .welcome-section {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
            background-color: #007bff;
        }
        
        .student-badge {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .progress-ring {
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }
        
        .progress-ring-circle {
            stroke: #e9ecef;
            stroke-width: 8;
            fill: transparent;
            r: 52;
            cx: 60;
            cy: 60;
        }
        
        .progress-ring-progress {
            stroke: #28a745;
            stroke-width: 8;
            stroke-linecap: round;
            fill: transparent;
            r: 52;
            cx: 60;
            cy: 60;
            stroke-dasharray: 327;
            stroke-dashoffset: 327;
            transition: stroke-dashoffset 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-user-graduate me-2"></i>ITE311 TALINO - Student Portal
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i><?= esc($user['name']) ?>
                        <span class="student-badge ms-2">STUDENT</span>
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
                        <i class="fas fa-graduation-cap me-2"></i>Welcome back, <?= esc($user['name']) ?>!
                    </h2>
                    <p class="mb-3 opacity-90">Continue your learning journey and track your academic progress.</p>
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
                <a class="nav-link active" href="<?= base_url('/student/dashboard') ?>">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/student/courses') ?>">
                    <i class="fas fa-book me-1"></i>My Courses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/student/browse') ?>">
                    <i class="fas fa-search me-1"></i>Browse Courses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/student/grades') ?>">
                    <i class="fas fa-chart-line me-1"></i>My Grades
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
                    <div class="stats-number"><?= $stats['enrolled_courses'] ?></div>
                    <div class="stats-label">Enrolled Courses</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card assignments">
                    <div class="stats-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stats-number"><?= $stats['completed_assignments'] ?></div>
                    <div class="stats-label">Completed</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card pending">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number"><?= $stats['pending_assignments'] ?></div>
                    <div class="stats-label">Pending</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card grade">
                    <div class="stats-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-number"><?= $stats['average_grade'] ?>%</div>
                    <div class="stats-label">Average Grade</div>
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
                                <a href="<?= base_url('/student/browse') ?>" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Browse Courses
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="<?= base_url('/student/courses') ?>" class="btn btn-success w-100">
                                    <i class="fas fa-book me-2"></i>My Courses
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="<?= base_url('/student/grades') ?>" class="btn btn-info w-100">
                                    <i class="fas fa-chart-line me-2"></i>View Grades
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-warning w-100">
                                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning Overview -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Assignments</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($stats['enrolled_courses'] > 0): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No upcoming assignments</h5>
                                <p class="text-muted">You're all caught up! Check back later for new assignments.</p>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No courses enrolled</h5>
                                <p class="text-muted">Start your learning journey by enrolling in a course.</p>
                                <a href="<?= base_url('/student/browse') ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-1"></i>Browse Available Courses
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Learning Progress</h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if ($stats['enrolled_courses'] > 0): ?>
                            <div class="progress-ring mb-3">
                                <svg class="progress-ring">
                                    <circle class="progress-ring-circle"></circle>
                                    <circle class="progress-ring-progress" style="stroke-dashoffset: <?= 327 - (327 * ($stats['average_grade'] / 100)) ?>"></circle>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h3 class="text-success mb-0"><?= $stats['average_grade'] ?>%</h3>
                                    <small class="text-muted">Overall</small>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-primary mb-1"><?= $stats['enrolled_courses'] ?></h5>
                                    <small class="text-muted">Courses</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-success mb-1"><?= $stats['completed_assignments'] ?></h5>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="py-4">
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Your progress will appear here once you enroll in courses.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Achievements</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-3">
                            <i class="fas fa-medal fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No achievements yet</p>
                            <small class="text-muted">Complete assignments to earn achievements!</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
