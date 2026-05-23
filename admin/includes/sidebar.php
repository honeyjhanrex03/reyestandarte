<?php
// Fetch unread messages count for the badge
$stmt = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0");
$unread_messages = $stmt->fetchColumn();

// Determine current page for active state
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<div id="sidebar-overlay"></div>

<nav id="sidebar" class="d-flex flex-column">
    <div class="brand">
        <img src="../assets/images/logo.png" alt="Logo" style="height: 40px; width: auto; object-fit: contain;"> Admin
    </div>
    <a href="index" class="<?= $current_page == 'index' ? 'active' : '' ?>">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="settings" class="<?= $current_page == 'settings' ? 'active' : '' ?>">
        <i class="bi bi-person-badge-fill"></i> Profile & Settings
    </a>
    <a href="services" class="<?= $current_page == 'services' ? 'active' : '' ?>">
        <i class="bi bi-layers-fill"></i> Services
    </a>
    <a href="skills" class="<?= $current_page == 'skills' ? 'active' : '' ?>">
        <i class="bi bi-star-fill"></i> Skills
    </a>
    <a href="certificates" class="<?= $current_page == 'certificates' ? 'active' : '' ?>">
        <i class="bi bi-award-fill"></i> Certificates
    </a>
    <a href="projects" class="<?= $current_page == 'projects' || $current_page == 'add_project' ? 'active' : '' ?>">
        <i class="bi bi-briefcase-fill"></i> Portfolio
    </a>
    <a href="messages" class="<?= $current_page == 'messages' ? 'active' : '' ?>">
        <i class="bi bi-envelope-paper-fill"></i> Messages
        <?php if($unread_messages > 0): ?>
            <span class="badge bg-primary rounded-pill ms-auto shadow-sm"><?= $unread_messages ?></span>
        <?php endif; ?>
    </a>
    <a href="logout" class="text-danger mt-auto mb-4 border-0">
        <i class="bi bi-box-arrow-left text-danger"></i> Logout
    </a>
</nav>
