<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Course Materials - ITE311 TALINO' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #2d3748;
        }

        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .course-header {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .course-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .material-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .material-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .material-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .material-card.pdf::before { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
        .material-card.doc::before { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); }
        .material-card.ppt::before { background: linear-gradient(135deg, #fd7e14 0%, #e55a00 100%); }
        .material-card.xls::before { background: linear-gradient(135deg, #198754 0%, #146c43 100%); }
        .material-card.zip::before { background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%); }

        .material-card:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .file-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .file-icon.pdf { color: #dc3545; }
        .file-icon.doc { color: #0d6efd; }
        .file-icon.ppt { color: #fd7e14; }
        .file-icon.xls { color: #198754; }
        .file-icon.zip { color: #6f42c1; }
        .file-icon.default { color: #6c757d; }

        .download-btn {
            background: var(--success-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            justify-content: center;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
            color: white;
        }

        .breadcrumb {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: white;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-graduation-cap me-2"></i>ITE311-TALINO
            </a>
            <div class="navbar-nav ms-auto">
                <?php if (session()->get('role') === 'student'): ?>
                    <a class="nav-link" href="<?= base_url('/student/dashboard') ?>">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                <?php else: ?>
                    <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                <?php endif; ?>
                <a class="nav-link" href="<?= base_url('/logout') ?>">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <?php if (session()->get('role') === 'student'): ?>
                        <a href="<?= base_url('/student/dashboard') ?>">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('/admin/dashboard') ?>">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    <?php endif; ?>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('/materials/student') ?>">Materials</a>
                </li>
                <li class="breadcrumb-item active"><?= esc($course['course_name']) ?></li>
            </ol>
        </nav>

        <!-- Course Header -->
        <div class="course-header">
            <div class="course-icon">
                <i class="fas fa-book"></i>
            </div>
            <h2 style="color: white; font-weight: 700; margin-bottom: 0.5rem;"><?= esc($course['course_name']) ?></h2>
            <p style="color: rgba(255, 255, 255, 0.8); font-size: 1.1rem; margin-bottom: 0;">
                <?= esc($course['course_code']) ?>
            </p>
        </div>

        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number"><?= count($materials) ?></div>
                <div class="stat-label">Total Materials</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php
                    $totalSize = 0;
                    foreach ($materials as $material) {
                        $totalSize += $material['file_size'] ?? 0;
                    }
                    echo number_format($totalSize / (1024 * 1024), 1);
                    ?>
                </div>
                <div class="stat-label">Total Size (MB)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php
                    $fileTypes = [];
                    foreach ($materials as $material) {
                        $ext = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                        $fileTypes[$ext] = true;
                    }
                    echo count($fileTypes);
                    ?>
                </div>
                <div class="stat-label">File Types</div>
            </div>
        </div>

        <!-- Materials Grid -->
        <?php if (empty($materials)): ?>
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-folder-open empty-icon"></i>
                        <h4 style="color: white; margin-bottom: 1rem;">No Materials Available</h4>
                        <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 2rem;">
                            This course doesn't have any learning materials yet. Check back later for updates.
                        </p>
                        <?php if (session()->get('role') === 'student'): ?>
                            <a href="<?= base_url('/student/dashboard') ?>" class="download-btn" style="max-width: 200px; margin: 0 auto;">
                                <i class="fas fa-arrow-left"></i>Back to Dashboard
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="material-grid">
                <?php foreach ($materials as $material): ?>
                    <?php
                    $extension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                    $iconClass = 'default';
                    $icon = 'fa-file';
                    
                    switch ($extension) {
                        case 'pdf':
                            $iconClass = 'pdf';
                            $icon = 'fa-file-pdf';
                            break;
                        case 'doc':
                        case 'docx':
                            $iconClass = 'doc';
                            $icon = 'fa-file-word';
                            break;
                        case 'ppt':
                        case 'pptx':
                            $iconClass = 'ppt';
                            $icon = 'fa-file-powerpoint';
                            break;
                        case 'xls':
                        case 'xlsx':
                            $iconClass = 'xls';
                            $icon = 'fa-file-excel';
                            break;
                        case 'zip':
                        case 'rar':
                            $iconClass = 'zip';
                            $icon = 'fa-file-archive';
                            break;
                    }
                    ?>
                    <div class="material-card <?= $iconClass ?>">
                        <div class="text-center mb-3">
                            <i class="fas <?= $icon ?> file-icon <?= $iconClass ?>"></i>
                        </div>
                        
                        <h5 style="color: white; margin-bottom: 1rem; font-weight: 600; line-height: 1.3;">
                            <?= esc($material['file_name']) ?>
                        </h5>
                        
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 1.5rem;">
                            <div class="mb-2">
                                <i class="fas fa-user me-2"></i>
                                <span>Uploaded by <?= esc($material['uploaded_by_name']) ?></span>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-calendar me-2"></i>
                                <span><?= date('M j, Y', strtotime($material['created_at'])) ?></span>
                            </div>
                            <?php if ($material['file_size']): ?>
                                <div>
                                    <i class="fas fa-weight me-2"></i>
                                    <span><?= number_format($material['file_size'] / 1024, 1) ?> KB</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                           class="download-btn"
                           title="Download <?= esc($material['file_name']) ?>">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add download tracking
            document.querySelectorAll('.download-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    console.log('Material downloaded:', this.getAttribute('title'));
                    
                    // Optional: Add loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                });
            });

            // Add hover effects for material cards
            document.querySelectorAll('.material-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>
