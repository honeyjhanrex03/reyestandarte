<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

$error = '';

if (!isset($_GET['id']) && !isset($_POST['edit_project'])) {
    header('Location: projects');
    exit;
}

$project_id = $_GET['id'] ?? $_POST['id'];

// Fetch existing project data
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: projects');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_project'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tech_stack = $_POST['tech_stack'] ?? '';
    $project_link = $_POST['project_link'] ?? '';
    $client = $_POST['client'] ?? '';
    $project_date = $_POST['project_date'] ?? '';
    
    // Main Image Upload
    $main_image = $project['image'];
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $target_dir = "../assets/images/projects/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["main_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid('main_') . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_file)) {
            if (!empty($main_image) && file_exists("../assets/images/projects/" . $main_image)) {
                unlink("../assets/images/projects/" . $main_image);
            }
            $main_image = $new_filename;
        }
    }

    if(empty($title) || empty($description)) {
        $error = "Title and Description are required!";
    } else {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, image = ?, tech_stack = ?, project_link = ?, client = ?, project_date = ? WHERE id = ?");
            $stmt->execute([$title, $description, $main_image, $tech_stack, $project_link, $client, $project_date, $project_id]);
            
            // Additional Images Upload
            if (isset($_FILES['additional_images']) && !empty($_FILES['additional_images']['name'][0])) {
                $count = count($_FILES['additional_images']['name']);
                for ($i = 0; $i < $count; $i++) {
                    if ($_FILES['additional_images']['error'][$i] == 0) {
                        $ext = pathinfo($_FILES['additional_images']['name'][$i], PATHINFO_EXTENSION);
                        $add_filename = uniqid('add_') . '.' . $ext;
                        $add_target = "../assets/images/projects/" . $add_filename;
                        
                        if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$i], $add_target)) {
                            $stmt_img = $pdo->prepare("INSERT INTO project_images (project_id, image_path) VALUES (?, ?)");
                            $stmt_img->execute([$project_id, $add_filename]);
                        }
                    }
                }
            }

            $pdo->commit();
            header('Location: projects?msg=updated');
            exit;
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = "Error updating project: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?= time() ?>">
    <style>
        .custom-form-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #eef0f4;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }
        .form-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.95rem;
            background-color: #f9fafb;
            color: #374151;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .help-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 6px;
        }
        .btn-black {
            background-color: #000000;
            color: #ffffff;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-black:hover {
            background-color: #1f2937;
            transform: translateY(-1px);
        }
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div id="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="toggle-btn me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h4>Portfolio / Edit Project</h4>
            </div>
            <div class="d-none d-md-block">
                <img src="https://ui-avatars.com/api/?name=Reynaldo+Estandarte&background=3b82f6&color=fff&bold=true" alt="Admin" class="rounded-circle" width="45">
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0">Edit Project</h1>
            <a href="projects" class="btn btn-light border shadow-sm">Back to Projects</a>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger border-0 rounded-4 shadow-sm"><?= $error ?></div>
        <?php endif; ?>

        <div class="custom-form-card">
            
            <form method="POST" action="edit_project" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $project['id'] ?>">
                
                <div class="mb-4">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($project['title']) ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($project['description']) ?></textarea>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">Tech Stack</label>
                        <input type="text" name="tech_stack" class="form-control" value="<?= htmlspecialchars($project['tech_stack']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Project Link</label>
                        <input type="url" name="project_link" class="form-control" value="<?= htmlspecialchars($project['project_link']) ?>">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">Client</label>
                        <input type="text" name="client" class="form-control" value="<?= htmlspecialchars($project['client']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Project Date</label>
                        <input type="text" name="project_date" class="form-control" value="<?= htmlspecialchars($project['project_date']) ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Main Project Image</label>
                    <input type="file" name="main_image" class="form-control" accept=".jpg,.jpeg,.png">
                    <?php if(!empty($project['image'])): ?>
                        <div class="mt-2">
                            <img src="../assets/images/projects/<?= htmlspecialchars($project['image']) ?>" width="100" class="rounded border">
                            <small class="text-muted d-block mt-1">Leave empty to keep existing image</small>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-5">
                    <label class="form-label">Add More Images (Carousel)</label>
                    <input type="file" name="additional_images[]" class="form-control" accept=".jpg,.jpeg,.png" multiple>
                    <div class="help-text">JPG/PNG only, max 2MB each. These will be added to the existing carousel.</div>
                </div>

                <button type="submit" name="edit_project" class="btn-black">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
