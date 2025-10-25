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

        .course-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid;
        }

        .course-section.course-1 { border-left-color: #667eea; }
        .course-section.course-2 { border-left-color: #f093fb; }
        .course-section.course-3 { border-left-color: #4facfe; }
        .course-section.course-4 { border-left-color: #43e97b; }
        .course-section.course-5 { border-left-color: #ffd700; }

        .material-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .material-item:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .file-icon {
            font-size: 2rem;
            margin-right: 1rem;
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
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
            color: white;
        }

        .course-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .course-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-size: 1.5rem;
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

        .search-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            padding: 0.75rem 1rem;
        }

        .search-box:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
            color: white;
        }

        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .stats-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
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
                <a class="nav-link" href="<?= base_url('/student/dashboard') ?>">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
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
                    <a href="<?= base_url('/student/dashboard') ?>">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active">Course Materials</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <h2 style="color: white; font-weight: 700; margin-bottom: 0.5rem;">
                    <i class="fas fa-folder-open me-2" style="color: #4facfe;"></i>My Course Materials
                </h2>
                <p style="color: rgba(255, 255, 255, 0.8);">Access learning materials from all your enrolled courses</p>
            </div>
            <div class="col-lg-4">
                <div class="stats-card">
                    <div class="stats-number"><?= count($materials) ?></div>
                    <div class="stats-label">Total Materials Available</div>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="position-relative">
                    <input type="text" class="form-control search-box" id="searchMaterials" placeholder="Search materials by name or course...">
                    <i class="fas fa-search position-absolute" style="right: 1rem; top: 50%; transform: translateY(-50%); color: rgba(255, 255, 255, 0.6);"></i>
                </div>
            </div>
        </div>

        <!-- Materials by Course -->
        <?php if (empty($materials)): ?>
            <div class="card">
                <div class="card-body text-center" style="padding: 4rem 2rem;">
                    <i class="fas fa-folder-open fa-4x mb-3" style="color: rgba(255, 255, 255, 0.3);"></i>
                    <h4 style="color: white; margin-bottom: 1rem;">No Materials Available</h4>
                    <p style="color: rgba(255, 255, 255, 0.7);">
                        You don't have any course materials yet. Materials will appear here once your instructors upload them.
                    </p>
                    <a href="<?= base_url('/student/dashboard') ?>" class="download-btn mt-3">
                        <i class="fas fa-arrow-left"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php
            // Group materials by course
            $courseGroups = [];
            foreach ($materials as $material) {
                $courseKey = $material['course_id'];
                if (!isset($courseGroups[$courseKey])) {
                    $courseGroups[$courseKey] = [
                        'course' => $material,
                        'materials' => []
                    ];
                }
                $courseGroups[$courseKey]['materials'][] = $material;
            }
            ?>

            <?php $courseIndex = 1; ?>
            <?php foreach ($courseGroups as $courseGroup): ?>
                <div class="course-section course-<?= $courseIndex ?>" data-course="<?= strtolower($courseGroup['course']['course_name']) ?>">
                    <div class="course-header">
                        <div class="course-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <h4 style="color: white; margin-bottom: 0.25rem;"><?= esc($courseGroup['course']['course_name']) ?></h4>
                            <p style="color: rgba(255, 255, 255, 0.7); margin: 0; font-size: 0.9rem;">
                                <?= esc($courseGroup['course']['course_code']) ?> • <?= count($courseGroup['materials']) ?> materials
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach ($courseGroup['materials'] as $material): ?>
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
                            <div class="col-lg-6 mb-3">
                                <div class="material-item" data-filename="<?= strtolower($material['file_name']) ?>">
                                    <div class="d-flex align-items-center">
                                        <i class="fas <?= $icon ?> file-icon <?= $iconClass ?>"></i>
                                        <div class="flex-grow-1">
                                            <h6 style="color: white; margin-bottom: 0.5rem;"><?= esc($material['file_name']) ?></h6>
                                            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem;">
                                                <div class="mb-1">
                                                    <i class="fas fa-user me-1"></i>Uploaded by <?= esc($material['uploaded_by_name']) ?>
                                                </div>
                                                <div>
                                                    <i class="fas fa-calendar me-1"></i><?= date('M j, Y', strtotime($material['created_at'])) ?>
                                                    <?php if ($material['file_size']): ?>
                                                        <span class="ms-3">
                                                            <i class="fas fa-weight me-1"></i><?= number_format($material['file_size'] / 1024, 1) ?> KB
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                                               class="download-btn"
                                               title="Download <?= esc($material['file_name']) ?>">
                                                <i class="fas fa-download"></i>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php $courseIndex++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchMaterials');
            const courseSections = document.querySelectorAll('.course-section');
            const materialItems = document.querySelectorAll('.material-item');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                if (searchTerm === '') {
                    // Show all courses and materials
                    courseSections.forEach(section => {
                        section.style.display = 'block';
                        const materials = section.querySelectorAll('.material-item');
                        materials.forEach(material => {
                            material.closest('.col-lg-6').style.display = 'block';
                        });
                    });
                } else {
                    // Filter materials and courses
                    courseSections.forEach(section => {
                        const courseName = section.dataset.course;
                        const materials = section.querySelectorAll('.material-item');
                        let hasVisibleMaterials = false;

                        materials.forEach(material => {
                            const fileName = material.dataset.filename;
                            const isMatch = fileName.includes(searchTerm) || courseName.includes(searchTerm);
                            
                            if (isMatch) {
                                material.closest('.col-lg-6').style.display = 'block';
                                hasVisibleMaterials = true;
                            } else {
                                material.closest('.col-lg-6').style.display = 'none';
                            }
                        });

                        // Show/hide course section based on whether it has visible materials
                        section.style.display = hasVisibleMaterials ? 'block' : 'none';
                    });
                }
            });

            // Add download tracking (optional)
            document.querySelectorAll('.download-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // You can add analytics tracking here
                    console.log('Material downloaded:', this.getAttribute('title'));
                });
            });
        });
    </script>
</body>
</html>
