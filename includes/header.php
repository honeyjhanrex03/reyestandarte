<?php
require_once 'db.php';
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['site_title'] ?? 'Portfolio') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index">
                <img src="assets/images/logo.png" alt="Reynaldo" style="height: 70px; width: auto; margin: -15px 0;">
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-1" style="color: var(--primary-color);"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" href="index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'about.php' ? 'active' : '' ?>" href="about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'services.php' ? 'active' : '' ?>" href="services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'skills.php' ? 'active' : '' ?>" href="skills">Skills</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'certificates.php' ? 'active' : '' ?>" href="certificates">Certificates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page == 'portfolio.php' ? 'active' : '' ?>" href="portfolio">Portfolio</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a class="btn btn-primary-custom" href="contact">Contact Me</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1 d-flex flex-column">
