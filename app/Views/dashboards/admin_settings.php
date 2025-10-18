<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Admin Dashboard</title>
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
        
        .setting-item {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .setting-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/admin/dashboard') ?>">
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
        <!-- Navigation Pills -->
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/users') ?>">
                    <i class="fas fa-users me-1"></i>User Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?= base_url('/admin/settings') ?>">
                    <i class="fas fa-cog me-1"></i>System Settings
                </a>
            </li>
        </ul>

        <div class="row">
            <div class="col-md-8">
                <!-- General Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>General Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">System Name</h6>
                                    <p class="text-muted mb-0">The name displayed throughout the application</p>
                                </div>
                                <div>
                                    <input type="text" class="form-control" value="ITE311 TALINO LMS" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Default User Role</h6>
                                    <p class="text-muted mb-0">Role assigned to new users upon registration</p>
                                </div>
                                <div>
                                    <select class="form-select">
                                        <option value="student" selected>Student</option>
                                        <option value="teacher">Teacher</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Registration Enabled</h6>
                                    <p class="text-muted mb-0">Allow new users to register accounts</p>
                                </div>
                                <div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Minimum Password Length</h6>
                                    <p class="text-muted mb-0">Minimum number of characters required for passwords</p>
                                </div>
                                <div>
                                    <input type="number" class="form-control" value="6" min="4" max="20" style="width: 80px;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Session Timeout</h6>
                                    <p class="text-muted mb-0">Automatic logout after inactivity (minutes)</p>
                                </div>
                                <div>
                                    <input type="number" class="form-control" value="60" min="15" max="480" style="width: 80px;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="setting-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Two-Factor Authentication</h6>
                                    <p class="text-muted mb-0">Require 2FA for admin accounts</p>
                                </div>
                                <div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- System Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">CodeIgniter Version</small>
                            <div class="fw-bold">4.x</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">PHP Version</small>
                            <div class="fw-bold"><?= phpversion() ?></div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Database</small>
                            <div class="fw-bold">MySQL</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Last Backup</small>
                            <div class="fw-bold text-warning">Never</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>System Tools</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="clearCache()">
                                <i class="fas fa-broom me-2"></i>Clear Cache
                            </button>
                            <button class="btn btn-outline-success" onclick="createBackup()">
                                <i class="fas fa-download me-2"></i>Create Backup
                            </button>
                            <button class="btn btn-outline-info" onclick="viewLogs()">
                                <i class="fas fa-file-alt me-2"></i>View System Logs
                            </button>
                            <button class="btn btn-outline-warning" onclick="maintenanceMode()">
                                <i class="fas fa-wrench me-2"></i>Maintenance Mode
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-success btn-lg me-2" onclick="saveSettings()">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                        <button class="btn btn-outline-secondary btn-lg" onclick="resetSettings()">
                            <i class="fas fa-undo me-2"></i>Reset to Defaults
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saveSettings() {
            // Placeholder for save functionality
            alert('Settings saved successfully! (This is a demo - actual save functionality would be implemented here)');
        }

        function resetSettings() {
            if (confirm('Are you sure you want to reset all settings to their default values?')) {
                alert('Settings reset to defaults! (This is a demo)');
            }
        }

        function clearCache() {
            if (confirm('Clear system cache? This may temporarily slow down the application.')) {
                alert('Cache cleared successfully! (This is a demo)');
            }
        }

        function createBackup() {
            alert('Creating system backup... (This is a demo - actual backup functionality would be implemented here)');
        }

        function viewLogs() {
            alert('System logs viewer would open here (This is a demo)');
        }

        function maintenanceMode() {
            if (confirm('Enable maintenance mode? This will make the site unavailable to users.')) {
                alert('Maintenance mode enabled! (This is a demo)');
            }
        }
    </script>
</body>
</html>
