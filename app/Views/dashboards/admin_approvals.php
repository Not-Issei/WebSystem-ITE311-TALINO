<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approvals - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px;
            padding: 30px;
        }
        .pending-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .pending-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff, #6610f2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .btn-approve {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            color: white;
        }
        .btn-approve:hover {
            background: linear-gradient(135deg, #20c997, #28a745);
            color: white;
        }
        .btn-reject {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            color: white;
        }
        .btn-reject:hover {
            background: linear-gradient(135deg, #c82333, #dc3545);
            color: white;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-clock text-info me-2"></i>Pending Approvals
                </h2>
                <p class="text-muted mb-0">Review and approve new user registrations</p>
            </div>
            <div>
                <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>

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

        <!-- Pending Users -->
        <?php if (!empty($pending_users)): ?>
            <div class="row">
                <?php foreach ($pending_users as $user): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card pending-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="user-avatar me-3">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= esc($user['name']) ?></h6>
                                        <small class="text-muted"><?= esc($user['email']) ?></small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Role</small>
                                            <span class="badge bg-primary"><?= ucfirst($user['role']) ?></span>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Department</small>
                                            <small><?= esc($user['department'] ?? 'N/A') ?></small>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($user['role'] === 'student'): ?>
                                    <div class="mb-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Student ID</small>
                                                <small><?= esc($user['student_id'] ?? 'N/A') ?></small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Year Level</small>
                                                <small><?= $user['year_level'] ? $user['year_level'] . ' Year' : 'N/A' ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <small class="text-muted d-block">Registration Date</small>
                                    <small><?= date('M j, Y g:i A', strtotime($user['created_at'])) ?></small>
                                </div>

                                <?php if ($user['phone']): ?>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Phone</small>
                                        <small><?= esc($user['phone']) ?></small>
                                    </div>
                                <?php endif; ?>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-approve btn-sm" onclick="approveUser(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')">
                                        <i class="fas fa-check me-1"></i>Approve
                                    </button>
                                    <button class="btn btn-reject btn-sm" onclick="rejectUser(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')">
                                        <i class="fas fa-times me-1"></i>Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4>No Pending Approvals</h4>
                <p class="text-muted">All user registrations have been processed.</p>
                <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt me-1"></i>Back to Dashboard
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function approveUser(userId, userName) {
            if (confirm(`Are you sure you want to approve ${userName}?`)) {
                fetch('<?= base_url('/admin/approve-user') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `user_id=${userId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'An error occurred while approving the user.');
                });
            }
        }

        function rejectUser(userId, userName) {
            if (confirm(`Are you sure you want to reject ${userName}? This will permanently delete their registration.`)) {
                fetch('<?= base_url('/admin/reject-user') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `user_id=${userId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'An error occurred while rejecting the user.');
                });
            }
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.main-container');
            const firstChild = container.firstElementChild.nextElementSibling;
            container.insertBefore(alertDiv, firstChild);
        }
    </script>
</body>
</html>
