<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Fetch current settings
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();

// Handle form submission
$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    try {
        $pdo->beginTransaction();
        
        $updateStmt = $pdo->prepare("UPDATE settings SET hero_title = ?, hero_tagline = ?, about_text = ?, email = ?, phone = ?, linkedin_url = ? WHERE id = 1");
        $updateStmt->execute([
            $_POST['hero_title'] ?? '',
            $_POST['hero_tagline'] ?? '',
            $_POST['about_text'] ?? '',
            $_POST['email'] ?? '',
            $_POST['phone'] ?? '',
            $_POST['linkedin_url'] ?? ''
        ]);
        
        $pdo->commit();
        $msg = 'Settings updated successfully!';
        $msg_type = 'success';
        
        // Refresh settings
        $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
        $settings = $stmt->fetch();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = 'Failed to update settings. Please try again.';
        $msg_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile & Settings - Admin</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?= time() ?>">
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div id="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="toggle-btn me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h4 class="mb-0">Profile & Settings</h4>
            </div>
            <div class="d-none d-md-block">
                <span class="text-muted me-3">Welcome back, <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></strong></span>
                <img src="https://ui-avatars.com/api/?name=Reynaldo+Estandarte&background=001B47&color=fff" alt="Admin" class="rounded-circle" width="40">
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4 p-md-5">
                <h5 class="card-title fw-bold text-primary mb-4" style="color: var(--primary-color) !important;">Manage Public Content</h5>
                
                <form method="POST" action="settings">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold">Hero Title</label>
                            <input type="text" name="hero_title" class="form-control bg-light" value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold">Hero Tagline</label>
                            <input type="text" name="hero_tagline" class="form-control bg-light" value="<?= htmlspecialchars($settings['hero_tagline'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted fw-bold">About Me Text</label>
                            <textarea name="about_text" class="form-control bg-light" rows="5" required><?= htmlspecialchars($settings['about_text'] ?? '') ?></textarea>
                        </div>
                        
                        <hr class="my-4 text-muted">
                        <h6 class="fw-bold mb-3">Contact Information</h6>
                        
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control bg-light" value="<?= htmlspecialchars($settings['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control bg-light" value="<?= htmlspecialchars($settings['phone'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-bold">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" class="form-control bg-light" value="<?= htmlspecialchars($settings['linkedin_url'] ?? '') ?>">
                        </div>
                        
                        <div class="col-12 mt-5 text-end">
                            <button type="submit" name="update_settings" class="btn btn-primary-custom px-5 py-2" style="background-color: var(--primary-color); color: white; border-radius: 8px;">
                                <i class="bi bi-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
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
    </script>

    <?php if($msg): ?>
    <script>
        Swal.fire({
            icon: '<?= $msg_type == "success" ? "success" : "error" ?>',
            title: '<?= $msg_type == "success" ? "Saved!" : "Error!" ?>',
            text: '<?= $msg ?>',
            confirmButtonColor: '#001B47'
        });
    </script>
    <?php endif; ?>
</body>
</html>
