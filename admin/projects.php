<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Handle Delete Project
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Get image to delete file
    $stmt = $pdo->prepare("SELECT image FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    
    if ($project && !empty($project['image']) && file_exists("../assets/images/projects/" . $project['image'])) {
        unlink("../assets/images/projects/" . $project['image']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: projects?msg=deleted');
    exit;
}

// Fetch all projects
$stmt = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?= time() ?>">
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div id="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="toggle-btn me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h4>Portfolio Projects</h4>
            </div>
            <div class="d-none d-md-block">
                <img src="https://ui-avatars.com/api/?name=Reynaldo+Estandarte&background=3b82f6&color=fff&bold=true" alt="Admin" class="rounded-circle" width="45">
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0 fw-bold">All Projects</h5>
                <a href="add_project" class="btn-accent text-decoration-none d-inline-block">
                    <i class="bi bi-plus-lg me-2"></i> Add New Project
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($projects as $p): ?>
                        <tr>
                            <td>
                                <img src="../assets/images/projects/<?= htmlspecialchars($p['image']) ?>" alt="img" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($p['title']) ?></td>
                            <td><span class="badge bg-light text-secondary border px-3 py-2 rounded-pill"><?= htmlspecialchars($p['category']) ?></span></td>
                            <td class="text-muted" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($p['description']) ?></td>
                            <td class="text-end text-nowrap">
                                <a href="edit_project?id=<?= $p['id'] ?>" class="btn btn-sm btn-light text-primary border-0 rounded-circle p-2">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button class="btn btn-sm btn-light text-danger border-0 rounded-circle p-2 ms-1" onclick="confirmDelete(<?= $p['id'] ?>)">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($projects)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No projects found. Start by adding one!</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->

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
        overlay.addEventListener('click', toggleSidebar);

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This project will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'projects?delete=' + id;
                }
            })
        }

        <?php if(isset($_GET['msg'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Project has been <?= $_GET['msg'] ?> successfully.',
            confirmButtonColor: '#3b82f6',
            timer: 2000,
            showConfirmButton: false
        });
        <?php endif; ?>
    </script>
</body>
</html>
