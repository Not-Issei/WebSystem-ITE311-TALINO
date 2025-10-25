<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard - ITE311 TALINO' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --admin-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --admin-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --admin-warning: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --admin-danger: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #2d3748;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="25" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="25" r="1" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            pointer-events: none;
            z-index: -1;
        }
        
        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: white !important;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }
        
        .admin-hero {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
            color: white;
        }
        
        .admin-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: adminFloat 8s ease-in-out infinite;
        }
        
        @keyframes adminFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            transition: all 0.4s ease;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }
        
        .management-card {
            text-align: center;
            padding: 3rem 2rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .management-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--admin-primary);
        }
        
        .management-card.users::before { background: var(--admin-primary); }
        .management-card.settings::before { background: var(--admin-warning); }
        .management-card.reports::before { background: var(--admin-success); }
        .management-card.security::before { background: var(--admin-danger); }
        
        .management-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            background: var(--admin-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .management-card.users .management-icon { background: var(--admin-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .management-card.settings .management-icon { background: var(--admin-warning); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .management-card.reports .management-icon { background: var(--admin-success); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .management-card.security .management-icon { background: var(--admin-danger); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .management-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }
        
        .management-desc {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            background: var(--admin-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .admin-badge {
            background: var(--admin-danger);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .action-btn {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .system-status {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #4ade80;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-graduation-cap me-2"></i>ITE311-TALINO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('/admin/dashboard') ?>">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/announcements') ?>">
                            <i class="fas fa-bullhorn me-1"></i>Announcements
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= $user['name'] ?? 'Admin' ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('/admin/dashboard') ?>">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('/logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Admin Hero Section -->
        <div class="admin-hero">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-4">
                            <i class="fas fa-crown" style="font-size: 4rem; color: #ffd700; filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.5));"></i>
                        </div>
                        <div>
                            <h1 class="mb-2" style="font-weight: 800; font-size: 3rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">System Administrator</h1>
                            <p class="mb-0" style="font-size: 1.2rem; opacity: 0.9;">Complete control over the ITE311-TALINO Learning Management System</p>
                        </div>
                    </div>
                    <div class="system-status">
                        <div class="status-indicator"></div>
                        <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">System Status: Online & Secure</span>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div style="background: var(--glass-bg); backdrop-filter: blur(20px); border-radius: 20px; padding: 2rem; border: 1px solid var(--glass-border);">
                        <h3 style="color: white; margin-bottom: 1rem;">Welcome, <?= esc($user['name']) ?>!</h3>
                        <div class="admin-badge mb-2">ADMINISTRATOR</div>
                        <p style="color: rgba(255, 255, 255, 0.8); margin: 0; font-size: 0.9rem;"><?= date('l, F j, Y • g:i A') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message (if any) -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= esc($error) ?>
            </div>
        <?php endif; ?>

        <!-- System Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['total_users'] ?? 0 ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['student_count'] ?? 0 ?></div>
                <div class="stat-label">Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['teacher_count'] ?? 0 ?></div>
                <div class="stat-label">Teachers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['admin_count'] ?? 0 ?></div>
                <div class="stat-label">Admins</div>
            </div>
        </div>

        <!-- Management Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card management-card users">
                    <div class="management-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h5 class="management-title">User Management</h5>
                    <p class="management-desc">Manage students, teachers, and administrative staff accounts</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card management-card settings">
                    <div class="management-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h5 class="management-title">System Settings</h5>
                    <p class="management-desc">Configure system parameters and global settings</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card management-card reports">
                    <div class="management-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="management-title">Analytics & Reports</h5>
                    <p class="management-desc">View detailed system analytics and generate reports</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card management-card security">
                    <div class="management-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="management-title">Security Center</h5>
                    <p class="management-desc">Monitor security, manage permissions and access logs</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Account Info -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body" style="padding: 2rem;">
                        <h4 class="mb-4" style="color: white; font-weight: 700;">
                            <i class="fas fa-bolt me-2" style="color: #ffd700;"></i>Quick Actions
                        </h4>
                        <div class="quick-actions">
                            <?php if ($pending_count > 0): ?>
                                <a href="<?= base_url('/admin/approvals') ?>" class="action-btn" style="position: relative;">
                                    <i class="fas fa-user-clock"></i>Pending Registrations
                                    <span class="badge bg-warning text-dark" style="position: absolute; top: -5px; right: -5px; font-size: 0.7rem;"><?= $pending_count ?></span>
                                </a>
                            <?php endif; ?>
                            <a href="#" class="action-btn">
                                <i class="fas fa-book-plus"></i>Create Course
                            </a>
                            <a href="#" class="action-btn">
                                <i class="fas fa-bullhorn"></i>New Announcement
                            </a>
                            <a href="#" class="action-btn">
                                <i class="fas fa-download"></i>Export Data
                            </a>
                            <a href="#" class="action-btn">
                                <i class="fas fa-backup"></i>System Backup
                            </a>
                            <a href="#" class="action-btn">
                                <i class="fas fa-tools"></i>Maintenance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body" style="padding: 2rem;">
                        <h5 class="mb-3" style="color: white; font-weight: 700;">
                            <i class="fas fa-user-shield me-2" style="color: #ffd700;"></i>Administrator Profile
                        </h5>
                        <div class="mb-3">
                            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 0.5rem;">Name</div>
                            <div style="color: white; font-weight: 600;"><?= esc($user['name']) ?></div>
                        </div>
                        <div class="mb-3">
                            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 0.5rem;">Email</div>
                            <div style="color: white; font-weight: 600;"><?= esc($user['email']) ?></div>
                        </div>
                        <div class="mb-3">
                            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 0.5rem;">Role</div>
                            <div class="admin-badge"><?= strtoupper($user['role']) ?></div>
                        </div>
                        <div class="mb-3">
                            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 0.5rem;">Last Login</div>
                            <div style="color: white; font-weight: 600;"><?= date('M j, Y • g:i A') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students and Teachers Lists -->
        <div class="row mt-4">
            <!-- Students List -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body" style="padding: 2rem;">
                        <h4 class="mb-4" style="color: white; font-weight: 700;">
                            <i class="fas fa-user-graduate me-2" style="color: #4facfe;"></i>Recent Students
                            <span class="badge" style="background: var(--success-gradient); font-size: 0.7rem; margin-left: 0.5rem;">
                                <?= count($students) ?> active
                            </span>
                        </h4>
                        
                        <?php if (empty($students)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-user-graduate fa-3x mb-3" style="color: rgba(255, 255, 255, 0.3);"></i>
                                <h6 style="color: rgba(255, 255, 255, 0.7);">No students registered yet</h6>
                            </div>
                        <?php else: ?>
                            <div style="max-height: 400px; overflow-y: auto;">
                                <?php foreach ($students as $student): ?>
                                    <div class="d-flex align-items-center mb-3 p-3" style="background: rgba(255, 255, 255, 0.1); border-radius: 12px; border-left: 4px solid #4facfe;">
                                        <div class="flex-shrink-0">
                                            <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                                <?= strtoupper(substr($student['name'], 0, 2)) ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 style="color: white; margin-bottom: 0.25rem; font-weight: 600;"><?= esc($student['name']) ?></h6>
                                            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem;">
                                                <div><?= esc($student['email']) ?></div>
                                                <div><strong>ID:</strong> <?= esc($student['student_id']) ?> • <strong>Dept:</strong> <?= esc($student['department']) ?></div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <small style="color: rgba(255, 255, 255, 0.6);">
                                                <?= date('M j', strtotime($student['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Teachers List -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body" style="padding: 2rem;">
                        <h4 class="mb-4" style="color: white; font-weight: 700;">
                            <i class="fas fa-chalkboard-teacher me-2" style="color: #43e97b;"></i>Recent Teachers
                            <span class="badge" style="background: var(--warning-gradient); font-size: 0.7rem; margin-left: 0.5rem;">
                                <?= count($teachers) ?> active
                            </span>
                        </h4>
                        
                        <?php if (empty($teachers)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-chalkboard-teacher fa-3x mb-3" style="color: rgba(255, 255, 255, 0.3);"></i>
                                <h6 style="color: rgba(255, 255, 255, 0.7);">No teachers registered yet</h6>
                            </div>
                        <?php else: ?>
                            <div style="max-height: 400px; overflow-y: auto;">
                                <?php foreach ($teachers as $teacher): ?>
                                    <div class="d-flex align-items-center mb-3 p-3" style="background: rgba(255, 255, 255, 0.1); border-radius: 12px; border-left: 4px solid #43e97b;">
                                        <div class="flex-shrink-0">
                                            <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--warning-gradient); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                                <?= strtoupper(substr($teacher['name'], 0, 2)) ?>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 style="color: white; margin-bottom: 0.25rem; font-weight: 600;"><?= esc($teacher['name']) ?></h6>
                                            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem;">
                                                <div><?= esc($teacher['email']) ?></div>
                                                <div><strong>ID:</strong> <?= esc($teacher['employee_id']) ?> • <strong>Dept:</strong> <?= esc($teacher['department']) ?></div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <small style="color: rgba(255, 255, 255, 0.6);">
                                                <?= date('M j', strtotime($teacher['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
