<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Fetch Analytics
$stmt = $pdo->query("SELECT COUNT(*) FROM projects");
$total_projects = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM services");
$total_services = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM messages");
$total_messages = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0");
$unread_messages = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reynaldo</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
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
                <h4 class="mb-0">Dashboard Overview</h4>
            </div>
            <div class="d-none d-md-block">
                <span class="text-muted me-3">Welcome back, <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></strong></span>
                <img src="https://ui-avatars.com/api/?name=Reynaldo+Estandarte&background=001B47&color=fff" alt="Admin" class="rounded-circle" width="40">
            </div>
        </div>

        <!-- Analytics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div class="details">
                        <h3><?= $total_projects ?></h3>
                        <p>Total Projects</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <div class="details">
                        <h3><?= $total_services ?></h3>
                        <p>Active Services</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon-box">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <div class="details">
                        <h3><?= $total_messages ?></h3>
                        <p>Total Messages</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions or Recent Activity could go here -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold text-primary" style="color: var(--primary-color) !important;">Getting Started</h5>
                <p class="card-text text-muted">Use the sidebar to navigate through your portfolio management sections. You can update your core profile information, add new services you offer, and upload projects to showcase to potential clients.</p>
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
</body>
</html>
