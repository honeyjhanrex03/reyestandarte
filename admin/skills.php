<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Handle Add Skill
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_skill'])) {
    $name = $_POST['name'];
    $level = $_POST['level'];
    $icon_class = $_POST['icon_class'] ?? '';
    
    // Icon Image Upload
    $icon_image = '';
    if (isset($_FILES['icon_image']) && $_FILES['icon_image']['error'] == 0) {
        $target_dir = "../assets/images/skills/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["icon_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid('skill_') . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["icon_image"]["tmp_name"], $target_file)) {
            $icon_image = $new_filename;
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO skills (name, level, icon_class, icon_image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $level, $icon_class, $icon_image]);
    
    header('Location: skills?msg=added');
    exit;
}

// Handle Edit Skill
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_skill'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $level = $_POST['level'];
    $icon_class = $_POST['icon_class'] ?? '';
    
    // Icon Image Upload
    $icon_image = $_POST['existing_image'];
    if (isset($_FILES['icon_image']) && $_FILES['icon_image']['error'] == 0) {
        $target_dir = "../assets/images/skills/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["icon_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid('skill_') . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["icon_image"]["tmp_name"], $target_file)) {
            // Delete old image
            if (!empty($icon_image) && file_exists("../assets/images/skills/" . $icon_image)) {
                unlink("../assets/images/skills/" . $icon_image);
            }
            $icon_image = $new_filename;
        }
    }
    
    $stmt = $pdo->prepare("UPDATE skills SET name = ?, level = ?, icon_class = ?, icon_image = ? WHERE id = ?");
    $stmt->execute([$name, $level, $icon_class, $icon_image, $id]);
    
    header('Location: skills?msg=updated');
    exit;
}

// Handle Delete Skill
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("SELECT icon_image FROM skills WHERE id = ?");
    $stmt->execute([$id]);
    $skill = $stmt->fetch();
    
    if ($skill && !empty($skill['icon_image']) && file_exists("../assets/images/skills/" . $skill['icon_image'])) {
        unlink("../assets/images/skills/" . $skill['icon_image']);
    }

    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: skills?msg=deleted');
    exit;
}

// Fetch all skills
$stmt = $pdo->query("SELECT * FROM skills ORDER BY level DESC");
$skills = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills - Admin</title>
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
                <h4>Skills</h4>
            </div>
            <div class="d-none d-md-block">
                <img src="https://ui-avatars.com/api/?name=Reynaldo+Estandarte&background=3b82f6&color=fff&bold=true" alt="Admin" class="rounded-circle" width="45">
            </div>
        </div>

        <h1 class="page-title">Manage Skills</h1>

        <div class="custom-form-card">
            <h5 class="form-section-title">Add Skill</h5>
            
            <form method="POST" action="skills" enctype="multipart/form-data">
                <div class="row mb-4">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <label class="form-label">Skill Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <label class="form-label">Level (0-100) *</label>
                        <input type="number" name="level" class="form-control" min="0" max="100" value="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Upload Icon Image (PNG/JPG/SVG)</label>
                        <input type="file" name="icon_image" class="form-control" accept=".png,.jpg,.jpeg,.svg,.gif">
                        <div class="help-text">Upload PNG, JPG, SVG, or GIF (max 2MB)</div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">OR Enter Icon Class/URL</label>
                    <input type="text" name="icon_class" class="form-control" placeholder="e.g., fab fa-html5 or https://cdn.jsdelivr.net/...">
                    <div class="help-text">Font Awesome: fab fa-html5 | Image URL: https://cdn.jsdelivr.net/... (Leave empty if uploading image)</div>
                </div>

                <button type="submit" name="add_skill" class="btn-black">Add Skill</button>
            </form>
        </div>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Level</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($skills as $s): ?>
                        <tr>
                            <td>
                                <?php if(!empty($s['icon_image'])): ?>
                                    <img src="../assets/images/skills/<?= htmlspecialchars($s['icon_image']) ?>" width="40" height="40" style="object-fit:contain;" alt="Icon">
                                <?php elseif(strpos($s['icon_class'], 'http') === 0): ?>
                                    <img src="<?= htmlspecialchars($s['icon_class']) ?>" width="40" height="40" style="object-fit:contain;" alt="Icon">
                                <?php else: ?>
                                    <i class="<?= htmlspecialchars($s['icon_class']) ?> fs-2 text-primary"></i>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($s['name']) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                        <div class="progress-bar bg-primary" style="width: <?= $s['level'] ?>%"></div>
                                    </div>
                                    <span class="text-muted small fw-bold"><?= $s['level'] ?>%</span>
                                </div>
                            </td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-light text-primary border-0 rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#editSkillModal<?= $s['id'] ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-light text-danger border-0 rounded-circle p-2 ms-1" onclick="confirmDelete(<?= $s['id'] ?>)">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Skill Modal -->
                        <div class="modal fade" id="editSkillModal<?= $s['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold fs-4">Edit Skill</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="skills" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($s['icon_image']) ?>">
                                            
                                            <div class="mb-4">
                                                <label class="form-label text-muted fw-bold">Skill Name *</label>
                                                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($s['name']) ?>">
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label text-muted fw-bold">Level (0-100) *</label>
                                                <input type="number" name="level" class="form-control" min="0" max="100" required value="<?= htmlspecialchars($s['level']) ?>">
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label text-muted fw-bold">Upload Icon Image (PNG/JPG/SVG)</label>
                                                <input type="file" name="icon_image" class="form-control" accept=".png,.jpg,.jpeg,.svg,.gif">
                                                <?php if(!empty($s['icon_image'])): ?>
                                                    <small class="text-muted d-block mt-1">Current: <?= htmlspecialchars($s['icon_image']) ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mb-5">
                                                <label class="form-label text-muted fw-bold">OR Enter Icon Class/URL</label>
                                                <input type="text" name="icon_class" class="form-control" value="<?= htmlspecialchars($s['icon_class']) ?>">
                                            </div>

                                            <button type="submit" name="edit_skill" class="btn-accent w-100 py-3 fs-5">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($skills)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No skills added yet.</td>
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
                text: "This skill will be deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'skills?delete=' + id;
                }
            })
        }

        <?php if(isset($_GET['msg'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Skill has been <?= $_GET['msg'] ?>.',
            confirmButtonColor: '#3b82f6',
            timer: 2000,
            showConfirmButton: false
        });
        <?php endif; ?>
    </script>
</body>
</html>
