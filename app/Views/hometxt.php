<?= $this->extend('template') ?>
<?= $this->section('content') ?>
    <div class="text-center">
        <h1 class="mt-4">Welcome to ITE311 TALINO LMS</h1>
        <p class="lead">Your Learning Management System for Academic Excellence</p>
        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Get Started</h5>
                        <p class="card-text">Create your account and begin your learning journey with us.</p>
                        <a href="<?= base_url('/register') ?>" class="btn btn-primary">Register Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-sign-in-alt fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Already a Member?</h5>
                        <p class="card-text">Sign in to access your courses, assignments, and dashboard.</p>
                        <a href="<?= base_url('/login') ?>" class="btn btn-success">Login</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-graduation-cap fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Learn & Grow</h5>
                        <p class="card-text">Access quality education and track your academic progress.</p>
                        <a href="#" class="btn btn-info">Explore Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
