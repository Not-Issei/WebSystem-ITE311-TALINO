<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - LMS TALINO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .registration-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .registration-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 500px;
            width: 100%;
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 2rem;
            text-align: center;
            border: none;
        }
        .student-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .btn-student {
            background: linear-gradient(135deg, #007bff, #6610f2);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-student:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 123, 255, 0.3);
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #6c757d;
            cursor: pointer;
        }
        .password-toggle:hover {
            color: #007bff;
        }
        .position-relative {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <div class="registration-card">
            <div class="card-header">
                <i class="fas fa-user-graduate student-icon"></i>
                <h3 class="mb-0">Join as Student</h3>
                <p class="mb-0 mt-2 opacity-75">Start your learning journey</p>
            </div>
            
            <div class="card-body p-4">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($validation->getErrors() as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/register/student') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-1"></i>Full Name
                        </label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= old('name', $old_input['name'] ?? '') ?>" 
                               placeholder="Enter your full name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email Address
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= old('email', $old_input['email'] ?? '') ?>" 
                               placeholder="Enter your email" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Password
                            </label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Enter password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirm" class="form-label">
                                <i class="fas fa-lock me-1"></i>Confirm Password
                            </label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                                       placeholder="Confirm password" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirm', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="department" class="form-label">
                                <i class="fas fa-graduation-cap me-1"></i>Program
                            </label>
                            <select class="form-control" id="department" name="department" required>
                                <option value="">Select Your Program</option>
                                <option value="Information Technology" <?= old('department', $old_input['department'] ?? '') === 'Information Technology' ? 'selected' : '' ?>>Information Technology</option>
                                <option value="Computer Science" <?= old('department', $old_input['department'] ?? '') === 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
                                <option value="Engineering" <?= old('department', $old_input['department'] ?? '') === 'Engineering' ? 'selected' : '' ?>>Engineering</option>
                                <option value="Business Administration" <?= old('department', $old_input['department'] ?? '') === 'Business Administration' ? 'selected' : '' ?>>Business Administration</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="year_level" class="form-label">
                                <i class="fas fa-layer-group me-1"></i>Year
                            </label>
                            <select class="form-control" id="year_level" name="year_level" required>
                                <option value="">Year</option>
                                <option value="1" <?= old('year_level', $old_input['year_level'] ?? '') === '1' ? 'selected' : '' ?>>1st</option>
                                <option value="2" <?= old('year_level', $old_input['year_level'] ?? '') === '2' ? 'selected' : '' ?>>2nd</option>
                                <option value="3" <?= old('year_level', $old_input['year_level'] ?? '') === '3' ? 'selected' : '' ?>>3rd</option>
                                <option value="4" <?= old('year_level', $old_input['year_level'] ?? '') === '4' ? 'selected' : '' ?>>4th</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone me-1"></i>Phone Number
                        </label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?= old('phone', $old_input['phone'] ?? '') ?>" 
                               placeholder="09XX XXX XXXX">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-student btn-lg text-white">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">Already have an account? 
                        <a href="<?= base_url('/login') ?>" class="text-primary text-decoration-none fw-bold">Login here</a>
                    </p>
                    <p class="mb-0 mt-2">
                        <a href="<?= base_url('/register') ?>" class="text-muted text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Back to registration options
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
