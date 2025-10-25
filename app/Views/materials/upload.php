<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Upload Materials - ITE311 TALINO' ?></title>
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

        .upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 3rem 2rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
        }

        .upload-area.dragover {
            border-color: #4facfe;
            background: rgba(79, 172, 254, 0.1);
        }

        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-danger-gradient {
            background: var(--secondary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-danger-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.3);
            color: white;
        }

        .material-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .material-item:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .material-item.pdf { border-left-color: #dc3545; }
        .material-item.doc { border-left-color: #0d6efd; }
        .material-item.ppt { border-left-color: #fd7e14; }
        .material-item.xls { border-left-color: #198754; }
        .material-item.zip { border-left-color: #6f42c1; }
        .material-item.default { border-left-color: #6c757d; }

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

        .alert {
            border: none;
            border-radius: 12px;
            backdrop-filter: blur(20px);
        }

        .alert-success {
            background: rgba(67, 233, 123, 0.2);
            border-left: 4px solid #43e97b;
            color: white;
        }

        .alert-danger {
            background: rgba(240, 147, 251, 0.2);
            border-left: 4px solid #f093fb;
            color: white;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: white;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-label {
            color: white;
            font-weight: 600;
            margin-bottom: 0.75rem;
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
                <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">
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
                    <a href="<?= base_url('/admin/dashboard') ?>">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('/admin/courses') ?>">Courses</a>
                </li>
                <li class="breadcrumb-item active"><?= esc($course['course_name']) ?> - Materials</li>
            </ol>
        </nav>

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

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Upload Form -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body" style="padding: 2rem;">
                        <h4 class="mb-4" style="color: white; font-weight: 700;">
                            <i class="fas fa-cloud-upload-alt me-2" style="color: #4facfe;"></i>Upload Material
                        </h4>
                        
                        <div class="mb-3">
                            <h6 style="color: rgba(255, 255, 255, 0.9);">Course: <?= esc($course['course_name']) ?></h6>
                            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;"><?= esc($course['course_code']) ?></p>
                        </div>

                        <form action="<?= base_url('/admin/course/' . $course['id'] . '/upload') ?>" method="post" enctype="multipart/form-data" id="uploadForm">
                            <?= csrf_field() ?>
                            <div class="mb-4">
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: rgba(255, 255, 255, 0.6);"></i>
                                    <h5 style="color: white; margin-bottom: 1rem;">Drop files here or click to browse</h5>
                                    <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 1rem;">
                                        Supported formats: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR
                                    </p>
                                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">Maximum file size: 10MB</p>
                                    <input type="file" name="material_file" id="material_file" class="d-none" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar">
                                </div>
                            </div>

                            <div id="fileInfo" class="mb-3" style="display: none;">
                                <div class="d-flex align-items-center p-3" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px;">
                                    <i class="fas fa-file fa-2x me-3" style="color: #4facfe;"></i>
                                    <div>
                                        <div id="fileName" style="color: white; font-weight: 600;"></div>
                                        <div id="fileSize" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;"></div>
                                    </div>
                                    <button type="button" class="btn btn-sm ms-auto" id="removeFile" style="color: rgba(255, 255, 255, 0.7);">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-gradient" id="uploadBtn" disabled>
                                    <i class="fas fa-upload me-2"></i>Upload Material
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Existing Materials -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="padding: 2rem;">
                        <h4 class="mb-4" style="color: white; font-weight: 700;">
                            <i class="fas fa-folder-open me-2" style="color: #43e97b;"></i>Course Materials
                            <span class="badge" style="background: var(--success-gradient); font-size: 0.7rem; margin-left: 0.5rem;">
                                <?= count($materials) ?> files
                            </span>
                        </h4>

                        <?php if (empty($materials)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-folder-open fa-3x mb-3" style="color: rgba(255, 255, 255, 0.3);"></i>
                                <h6 style="color: rgba(255, 255, 255, 0.7);">No materials uploaded yet</h6>
                                <p style="color: rgba(255, 255, 255, 0.5); font-size: 0.9rem;">Upload your first material to get started</p>
                            </div>
                        <?php else: ?>
                            <div style="max-height: 500px; overflow-y: auto;">
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
                                    <div class="material-item <?= $iconClass ?>">
                                        <div class="d-flex align-items-center">
                                            <i class="fas <?= $icon ?> file-icon <?= $iconClass ?>"></i>
                                            <div class="flex-grow-1">
                                                <h6 style="color: white; margin-bottom: 0.25rem;"><?= esc($material['file_name']) ?></h6>
                                                <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem;">
                                                    <span><i class="fas fa-user me-1"></i><?= esc($material['uploaded_by_name']) ?></span>
                                                    <span class="ms-3"><i class="fas fa-calendar me-1"></i><?= date('M j, Y', strtotime($material['created_at'])) ?></span>
                                                    <?php if ($material['file_size']): ?>
                                                        <span class="ms-3"><i class="fas fa-weight me-1"></i><?= number_format($material['file_size'] / 1024, 1) ?> KB</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                                                   class="btn btn-sm me-2" 
                                                   style="background: var(--success-gradient); color: white; border-radius: 8px;"
                                                   title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="<?= base_url('/materials/delete/' . $material['id']) ?>" 
                                                   class="btn btn-sm btn-danger-gradient"
                                                   onclick="return confirm('Are you sure you want to delete this material?')"
                                                   title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('material_file');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const uploadBtn = document.getElementById('uploadBtn');
            const removeFileBtn = document.getElementById('removeFile');

            // Click to browse
            uploadArea.addEventListener('click', () => fileInput.click());

            // Drag and drop
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect();
                }
            });

            // File input change
            fileInput.addEventListener('change', handleFileSelect);

            // Remove file
            removeFileBtn.addEventListener('click', () => {
                fileInput.value = '';
                fileInfo.style.display = 'none';
                uploadBtn.disabled = true;
            });

            function handleFileSelect() {
                const file = fileInput.files[0];
                if (file) {
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);
                    fileInfo.style.display = 'block';
                    uploadBtn.disabled = false;
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
</body>
</html>
