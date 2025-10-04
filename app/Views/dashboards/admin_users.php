<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Dashboard</title>
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
        
        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .role-admin { background-color: #dc3545; color: white; }
        .role-teacher { background-color: #28a745; color: white; }
        .role-student { background-color: #007bff; color: white; }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
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

        <!-- Navigation Pills -->
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?= base_url('/admin/users') ?>">
                    <i class="fas fa-users me-1"></i>User Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/settings') ?>">
                    <i class="fas fa-cog me-1"></i>System Settings
                </a>
            </li>
        </ul>

        <!-- User Management -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>User Management</h5>
                    <div>
                        <span class="badge bg-light text-dark me-2">Total: <?= count($users) ?> users</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($users)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No users found</h5>
                        <p class="text-muted">Users will appear here when they register.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $userItem): ?>
                                    <tr>
                                        <td><?= esc($userItem['id']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <?= esc($userItem['name']) ?>
                                            </div>
                                        </td>
                                        <td><?= esc($userItem['email']) ?></td>
                                        <td>
                                            <select class="form-select form-select-sm role-select" 
                                                    data-user-id="<?= esc($userItem['id']) ?>"
                                                    <?= ($userItem['id'] == $user['id']) ? 'disabled' : '' ?>>
                                                <option value="admin" <?= ($userItem['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                                                <option value="teacher" <?= ($userItem['role'] == 'teacher') ? 'selected' : '' ?>>Teacher</option>
                                                <option value="student" <?= ($userItem['role'] == 'student') ? 'selected' : '' ?>>Student</option>
                                            </select>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('M j, Y', strtotime($userItem['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($userItem['id'] != $user['id']): ?>
                                                <button class="btn btn-sm btn-outline-primary me-1" 
                                                        onclick="viewUser(<?= esc($userItem['id']) ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete(<?= esc($userItem['id']) ?>, '<?= esc($userItem['name']) ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Current User</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Role Distribution Summary -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-shield fa-2x text-danger mb-2"></i>
                        <h3 class="text-danger"><?= count(array_filter($users, function($u) { return $u['role'] == 'admin'; })) ?></h3>
                        <p class="text-muted mb-0">Administrators</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
                        <h3 class="text-success"><?= count(array_filter($users, function($u) { return $u['role'] == 'teacher'; })) ?></h3>
                        <p class="text-muted mb-0">Teachers</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                        <h3 class="text-primary"><?= count(array_filter($users, function($u) { return $u['role'] == 'student'; })) ?></h3>
                        <p class="text-muted mb-0">Students</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle role changes
        document.querySelectorAll('.role-select').forEach(select => {
            select.addEventListener('change', function() {
                const userId = this.dataset.userId;
                const newRole = this.value;
                
                if (confirm(`Are you sure you want to change this user's role to ${newRole}?`)) {
                    updateUserRole(userId, newRole);
                } else {
                    // Reset to original value if cancelled
                    this.selectedIndex = [...this.options].findIndex(option => option.defaultSelected);
                }
            });
        });

        function updateUserRole(userId, role) {
            fetch('<?= base_url('/admin/update-user-role') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_id=${userId}&role=${role}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', data.message);
                    // Reload page after a short delay
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                showAlert('danger', 'An error occurred while updating the user role.');
            });
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild.nextSibling);
        }

        function viewUser(userId) {
            // Placeholder for user details modal
            alert('User details functionality would be implemented here.');
        }

        function confirmDelete(userId, userName) {
            if (confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
                // Placeholder for delete functionality
                alert('Delete functionality would be implemented here.');
            }
        }
    </script>
</body>
</html>
