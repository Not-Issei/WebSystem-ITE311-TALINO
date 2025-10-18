<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ITE311 TALINO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px 15px 0 0 !important;
            color: white;
            text-align: center;
            padding: 2rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
        .form-control {
            border-radius: 25px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0"><i class="fas fa-user-plus me-2"></i>Create Account</h3>
                        <p class="mb-0 mt-2">Join ITE311 TALINO Learning Management System</p>
                    </div>
                    <div class="card-body p-4">
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

                        <?php 
                        $validation = session()->getFlashdata('validation');
                        if ($validation): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    <?php foreach ($validation->getErrors() as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="text-center mb-4">
                            <p class="text-muted">Choose your registration type:</p>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <a href="<?= base_url('/register/admin') ?>" class="btn btn-outline-danger w-100 py-3">
                                    <i class="fas fa-user-shield fa-2x d-block mb-2"></i>
                                    <strong>Administrator</strong>
                                    <small class="d-block text-muted">System Administration Access</small>
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="<?= base_url('/register/teacher') ?>" class="btn btn-outline-success w-100 py-3">
                                    <i class="fas fa-chalkboard-teacher fa-2x d-block mb-2"></i>
                                    <strong>Teacher/Educator</strong>
                                    <small class="d-block text-muted">Create and manage courses</small>
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="<?= base_url('/register/student') ?>" class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-user-graduate fa-2x d-block mb-2"></i>
                                    <strong>Student</strong>
                                    <small class="d-block text-muted">Access courses and learning materials</small>
                                </a>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-0">Already have an account? 
                                <a href="<?= base_url('/login') ?>" class="text-decoration-none fw-bold">
                                    Sign In Here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
