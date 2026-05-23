<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Handle Add Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon = $_POST['icon'] ?? '';
    
    // Icon Image Upload
    $icon_image = '';
    if (isset($_FILES['icon_image']) && $_FILES['icon_image']['error'] == 0) {
        $target_dir = "../assets/images/services/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["icon_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid('service_') . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["icon_image"]["tmp_name"], $target_file)) {
            $icon_image = $new_filename;
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, icon_image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $icon, $icon_image]);
    
    header('Location: services?msg=added');
    exit;
}

// Handle Edit Service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_service'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon = $_POST['icon'] ?? '';
    
    // Icon Image Upload
    $icon_image = $_POST['existing_image'];
    if (isset($_FILES['icon_image']) && $_FILES['icon_image']['error'] == 0) {
        $target_dir = "../assets/images/services/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["icon_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid('service_') . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["icon_image"]["tmp_name"], $target_file)) {
            // Delete old image
            if (!empty($icon_image) && file_exists("../assets/images/services/" . $icon_image)) {
                unlink("../assets/images/services/" . $icon_image);
            }
            $icon_image = $new_filename;
        }
    }
    
    $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ?, icon = ?, icon_image = ? WHERE id = ?");
    $stmt->execute([$title, $description, $icon, $icon_image, $id]);
    
    header('Location: services?msg=updated');
    exit;
}

// Handle Delete Service
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Get image to delete file
    $stmt = $pdo->prepare("SELECT icon_image FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $service = $stmt->fetch();
    
    if ($service && !empty($service['icon_image']) && file_exists("../assets/images/services/" . $service['icon_image'])) {
        unlink("../assets/images/services/" . $service['icon_image']);
    }

    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: services?msg=deleted');
    exit;
}

// Fetch all services
$stmt = $pdo->query("SELECT * FROM services ORDER BY id DESC");
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?= time() ?>">
    <style>
        .custom-form-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #eef0f4;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            margin-bottom: 40px;
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
                <h4>Services</h4>
            </div>
            <div class="d-none d-md-block">
                <img src="https://ui-avatars.com/api/?name=Reynaldo+Estandarte&background=3b82f6&color=fff&bold=true" alt="Admin" class="rounded-circle" width="45">
            </div>
        </div>

        <h1 class="page-title">Manage Services</h1>

        <div class="custom-form-card">
            <h5 class="form-section-title">Add Service</h5>
            
            <form method="POST" action="services" enctype="multipart/form-data">
                <div class="row mb-4">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">Service Name *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Icon (Font Awesome Class or Image URL)</label>
                        <input type="text" name="icon" class="form-control" placeholder="e.g., fas fa-code or https://example.com/icon.png">
                        <div class="help-text">Example: fas fa-code, fas fa-paint-brush, or image URL</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Or Upload Icon Image</label>
                    <input type="file" name="icon_image" class="form-control" accept=".png,.jpg,.jpeg,.svg,.gif">
                    <div class="help-text">PNG, JPG, SVG, or GIF (max 2MB)</div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" name="add_service" class="btn-black">Add Service</button>
            </form>
        </div>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($services as $s): ?>
                        <tr>
                            <td>
                                <?php if(!empty($s['icon_image'])): ?>
                                    <img src="../assets/images/services/<?= htmlspecialchars($s['icon_image']) ?>" width="40" height="40" style="object-fit:contain;" alt="Icon">
                                <?php else: ?>
                                    <i class="<?= htmlspecialchars($s['icon']) ?> fs-2 text-primary"></i>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($s['title']) ?></td>
                            <td class="text-muted" style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($s['description']) ?></td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-light text-primary border-0 rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#editServiceModal<?= $s['id'] ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-light text-danger border-0 rounded-circle p-2 ms-1" onclick="confirmDelete(<?= $s['id'] ?>)">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Service Modal -->
                        <div class="modal fade" id="editServiceModal<?= $s['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold fs-4">Edit Service</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="services" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($s['icon_image']) ?>">
                                            
                                            <div class="mb-4">
                                                <label class="form-label text-muted fw-bold">Service Name *</label>
                                                <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($s['title']) ?>">
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label text-muted fw-bold">Icon (Class or URL)</label>
                                                <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($s['icon']) ?>">
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label text-muted fw-bold">Or Upload Icon Image</label>
                                                <input type="file" name="icon_image" class="form-control" accept=".png,.jpg,.jpeg,.svg,.gif">
                                                <?php if(!empty($s['icon_image'])): ?>
                                                    <small class="text-muted d-block mt-1">Current: <?= htmlspecialchars($s['icon_image']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mb-5">
                                                <label class="form-label text-muted fw-bold">Description *</label>
                                                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($s['description']) ?></textarea>
                                            </div>

                                            <button type="submit" name="edit_service" class="btn-accent w-100 py-3 fs-5">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($services)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No services configured yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This service will be deleted from your portfolio.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'services?delete=' + id;
                }
            })
        }

        <?php if(isset($_GET['msg'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Service has been <?= $_GET['msg'] ?>.',
            confirmButtonColor: '#3b82f6',
            timer: 2000,
            showConfirmButton: false
        });
        <?php endif; ?>
    </script>
</body>
</html>
