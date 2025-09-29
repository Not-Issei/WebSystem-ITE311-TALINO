<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ITE311 TALINO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color: #2c3e50;
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
            background-color: #34495e;
            color: white;
            border-radius: 8px 8px 0 0 !important;
            border-bottom: none;
            padding: 1rem 1.5rem;
        }
        
        .stats-card {
            text-align: center;
            padding: 2rem 1rem;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-card.courses .stats-icon { color: #3498db; }
        .stats-card.assignments .stats-icon { color: #e74c3c; }
        .stats-card.quizzes .stats-icon { color: #f39c12; }
        .stats-card.progress .stats-icon { color: #27ae60; }
        
        .btn-action {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid #ecf0f1;
            display: flex;
            align-items: center;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1rem;
        }
        
        .activity-icon.success { background-color: #d4edda; color: #155724; }
        .activity-icon.info { background-color: #d1ecf1; color: #0c5460; }
        .activity-icon.warning { background-color: #fff3cd; color: #856404; }
        
        .notification-item {
            padding: 0.75rem 1rem;
            border-left: 3px solid #3498db;
            background-color: #f8f9fa;
            margin-bottom: 0.75rem;
            border-radius: 0 4px 4px 0;
        }
        
        .notification-item.urgent { border-left-color: #e74c3c; }
        .notification-item.info { border-left-color: #3498db; }
        
        .calendar {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .calendar-header {
            background-color: #34495e;
            color: white;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background-color: #ecf0f1;
            padding: 1px;
        }
        
        .calendar-day-header {
            background-color: #bdc3c7;
            padding: 0.5rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.8rem;
            color: #2c3e50;
        }
        
        .calendar-day {
            background-color: white;
            padding: 0.75rem 0.5rem;
            text-align: center;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .calendar-day:hover {
            background-color: #ecf0f1;
        }
        
        .calendar-day.other-month {
            color: #bdc3c7;
        }
        
        .calendar-day.today {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        
        .calendar-day.has-event {
            background-color: #e8f5e8;
            border-left: 3px solid #27ae60;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>ITE311 TALINO LMS
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i><?= esc($user['name']) ?>
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
        <!-- Welcome Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-12">
                    <h2 class="mb-2">Welcome back, <?= esc($user['name']) ?>!</h2>
                    <p class="mb-3 opacity-90">Here's what's happening with your learning today.</p>
                    <div class="d-flex flex-wrap gap-3 mb-2">
                        <span class="badge bg-white bg-opacity-20 px-3 py-2">
                            <i class="fas fa-envelope me-1"></i><?= esc($user['email']) ?>
                        </span>
                        <span class="badge bg-white bg-opacity-20 px-3 py-2">
                            <i class="fas fa-user-tag me-1"></i><?= ucfirst(esc($user['role'])) ?>
                        </span>
                    </div>
                    <p class="mb-0 opacity-75">
                        Good <?= date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') ?>! Today is <?= date('l, F j, Y') ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card courses">
                    <div class="stats-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Enrolled Courses</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card assignments">
                    <div class="stats-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Pending Assignments</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card quizzes">
                    <div class="stats-icon">
                        <i class="fas fa-quiz"></i>
                    </div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Available Quizzes</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card progress">
                    <div class="stats-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-number">0%</div>
                    <div class="stats-label">Overall Progress</div>
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
                                <a href="#" class="btn btn-primary w-100 btn-action">
                                    <i class="fas fa-search me-2"></i>Browse Courses
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-success w-100 btn-action">
                                    <i class="fas fa-plus me-2"></i>Enroll Course
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-info w-100 btn-action">
                                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="<?= base_url('/logout') ?>" class="btn btn-danger w-100 btn-action">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Notifications -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity to display.</p>
                            <small class="text-muted">Start by enrolling in a course to see your activity here.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No notifications.</p>
                            <small class="text-muted">You're all caught up!</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="calendar">
                    <div class="calendar-header">
                        <h5 class="mb-0"><i class="fas fa-calendar me-2"></i><?= date('F Y') ?></h5>
                    </div>
                    <div class="calendar-grid">
                        <!-- Day headers -->
                        <div class="calendar-day-header">Sun</div>
                        <div class="calendar-day-header">Mon</div>
                        <div class="calendar-day-header">Tue</div>
                        <div class="calendar-day-header">Wed</div>
                        <div class="calendar-day-header">Thu</div>
                        <div class="calendar-day-header">Fri</div>
                        <div class="calendar-day-header">Sat</div>
                        
                        <?php
                        // Get current date info
                        $currentYear = date('Y');
                        $currentMonth = date('n');
                        $currentDay = date('j');
                        $firstDayOfMonth = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
                        $daysInMonth = date('t', $firstDayOfMonth);
                        $dayOfWeek = date('w', $firstDayOfMonth);
                        
                        // Previous month days
                        $prevMonth = $currentMonth - 1;
                        $prevYear = $currentYear;
                        if ($prevMonth == 0) {
                            $prevMonth = 12;
                            $prevYear--;
                        }
                        $daysInPrevMonth = date('t', mktime(0, 0, 0, $prevMonth, 1, $prevYear));
                        
                        // Fill in previous month days
                        for ($i = $dayOfWeek - 1; $i >= 0; $i--) {
                            $day = $daysInPrevMonth - $i;
                            echo '<div class="calendar-day other-month">' . $day . '</div>';
                        }
                        
                        // Current month days
                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $classes = 'calendar-day';
                            if ($day == $currentDay) {
                                $classes .= ' today';
                            }
                            // No events for now - can be added later when integrating with database
                            echo '<div class="' . $classes . '">' . $day . '</div>';
                        }
                        
                        // Next month days to fill the grid
                        $totalCells = 42; // 6 rows × 7 days
                        $cellsFilled = $dayOfWeek + $daysInMonth;
                        $remainingCells = $totalCells - $cellsFilled;
                        
                        for ($day = 1; $day <= $remainingCells && $cellsFilled < $totalCells; $day++) {
                            echo '<div class="calendar-day other-month">' . $day . '</div>';
                            $cellsFilled++;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
