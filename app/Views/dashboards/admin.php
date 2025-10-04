<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ITE311 TALINO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            background-color: #dc3545;
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
        
        .stats-card.users .stats-icon { color: #007bff; }
        .stats-card.users .stats-number { color: #007bff; }
        .stats-card.courses .stats-icon { color: #28a745; }
        .stats-card.courses .stats-number { color: #28a745; }
        .stats-card.teachers .stats-icon { color: #ffc107; }
        .stats-card.teachers .stats-number { color: #ffc107; }
        .stats-card.students .stats-icon { color: #17a2b8; }
        .stats-card.students .stats-number { color: #17a2b8; }
        
        .welcome-section {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            background-color: #dc3545;
        }
        
        .admin-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
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
                <i class="fas fa-shield-alt me-2"></i>ITE311 TALINO - Admin Panel
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield me-1"></i><?= esc($user['name']) ?>
                        <span class="admin-badge ms-2">ADMIN</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/admin/settings') ?>"><i class="fas fa-cog me-2"></i>Settings</a></li>
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
                        <i class="fas fa-crown me-2"></i>Welcome, Administrator <?= esc($user['name']) ?>!
                    </h2>
                    <p class="mb-3 opacity-90">Manage your Learning Management System from this central control panel.</p>
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
                <a class="nav-link active" href="<?= base_url('/admin/dashboard') ?>">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/approvals') ?>">
                    <i class="fas fa-clock me-1"></i>Pending Approvals
                    <?php if (($stats['pending_users'] ?? 0) > 0): ?>
                        <span class="badge bg-danger ms-1"><?= $stats['pending_users'] ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/users') ?>">
                    <i class="fas fa-users me-1"></i>User Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/settings') ?>">
                    <i class="fas fa-cog me-1"></i>System Settings
                </a>
            </li>
        </ul>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card users">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number"><?= $stats['total_users'] ?></div>
                    <div class="stats-label">Total Users</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card courses">
                    <div class="stats-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stats-number"><?= $stats['total_courses'] ?></div>
                    <div class="stats-label">Total Courses</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card teachers">
                    <div class="stats-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stats-number"><?= $stats['teacher_count'] ?></div>
                    <div class="stats-label">Teachers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card students">
                    <div class="stats-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stats-number"><?= $stats['student_count'] ?></div>
                    <div class="stats-label">Students</div>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-calendar-day fa-2x text-info me-3"></i>
                            <div>
                                <h3 class="text-info mb-0"><?= $stats['today_registrations'] ?></h3>
                                <p class="text-muted mb-0">Today's Registrations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-calendar-week fa-2x text-warning me-3"></i>
                            <div>
                                <h3 class="text-warning mb-0"><?= $stats['recent_registrations'] ?></h3>
                                <p class="text-muted mb-0">This Week's Registrations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        <button class="btn btn-sm btn-outline-light" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh Data
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="<?= base_url('/admin/users') ?>" class="btn btn-primary w-100">
                                    <i class="fas fa-users me-2"></i>Manage Users
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="<?= base_url('/admin/settings') ?>" class="btn btn-success w-100">
                                    <i class="fas fa-cog me-2"></i>System Settings
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-info w-100">
                                    <i class="fas fa-chart-bar me-2"></i>View Reports
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="#" class="btn btn-warning w-100">
                                    <i class="fas fa-database me-2"></i>Backup System
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>User Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="mb-2">
                                    <i class="fas fa-user-shield fa-2x text-danger"></i>
                                </div>
                                <h4 class="text-danger"><?= $stats['admin_count'] ?></h4>
                                <small class="text-muted">Admins</small>
                            </div>
                            <div class="col-4">
                                <div class="mb-2">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-warning"></i>
                                </div>
                                <h4 class="text-warning"><?= $stats['teacher_count'] ?></h4>
                                <small class="text-muted">Teachers</small>
                            </div>
                            <div class="col-4">
                                <div class="mb-2">
                                    <i class="fas fa-user-graduate fa-2x text-info"></i>
                                </div>
                                <h4 class="text-info"><?= $stats['student_count'] ?></h4>
                                <small class="text-muted">Students</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Recent Users</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($stats['recent_users'])): ?>
                            <?php foreach ($stats['recent_users'] as $recentUser): ?>
                                <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                                    <div class="flex-shrink-0">
                                        <?php
                                        $iconClass = 'fas fa-user';
                                        $iconColor = 'text-primary';
                                        switch($recentUser['role']) {
                                            case 'admin':
                                                $iconClass = 'fas fa-user-shield';
                                                $iconColor = 'text-danger';
                                                break;
                                            case 'teacher':
                                                $iconClass = 'fas fa-chalkboard-teacher';
                                                $iconColor = 'text-success';
                                                break;
                                            case 'student':
                                                $iconClass = 'fas fa-user-graduate';
                                                $iconColor = 'text-primary';
                                                break;
                                        }
                                        ?>
                                        <i class="<?= $iconClass ?> fa-lg <?= $iconColor ?>"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><?= esc($recentUser['name']) ?></h6>
                                        <p class="mb-0 text-muted small">
                                            <?= esc($recentUser['email']) ?> • 
                                            <span class="badge bg-<?= $recentUser['role'] == 'admin' ? 'danger' : ($recentUser['role'] == 'teacher' ? 'success' : 'primary') ?> badge-sm">
                                                <?= ucfirst($recentUser['role']) ?>
                                            </span>
                                        </p>
                                        <small class="text-muted">
                                            Registered <?= date('M j, Y g:i A', strtotime($recentUser['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No users registered yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-refresh the page every 60 seconds to show new registrations
        setInterval(function() {
            // Only refresh if user is still on the page (not navigated away)
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 60000); // 60 seconds

        // Add a visual indicator when refreshing
        function refreshDashboard() {
            const refreshBtn = document.querySelector('button[onclick="location.reload()"]');
            const originalText = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Refreshing...';
            refreshBtn.disabled = true;
            
            setTimeout(() => {
                location.reload();
            }, 500);
        }

        // Update the refresh button to use the new function
        document.querySelector('button[onclick="location.reload()"]').setAttribute('onclick', 'refreshDashboard()');
    </script>
</body>
</html>
