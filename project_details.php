<?php 
require_once 'includes/db.php';

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: portfolio");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if(!$project) {
    header("Location: portfolio");
    exit;
}

// Fetch additional images if any
$stmt_img = $pdo->prepare("SELECT * FROM project_images WHERE project_id = ?");
$stmt_img->execute([$id]);
$additional_images = $stmt_img->fetchAll();

$all_images = [];
if(!empty($project['image'])) {
    $all_images[] = $project['image'];
}
foreach($additional_images as $img) {
    $all_images[] = $img['image_path'];
}

include 'includes/header.php'; 
?>

<!-- Premium Header -->
<header class="page-header pb-5">
    <div class="container">
        <span class="badge bg-white text-primary px-3 py-2 rounded-pill mb-4 shadow-sm" style="color: var(--primary-color) !important; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
            <?= htmlspecialchars($project['category'] ?? 'Portfolio Project') ?>
        </span>
        <h1 class="display-4 fw-bolder mb-3" style="max-width: 900px; margin: 0 auto; line-height: 1.2;">
            <?= htmlspecialchars($project['title']) ?>
        </h1>
    </div>
</header>

<section class="section-padding bg-slate" style="margin-top: -80px; position: relative; z-index: 10;">
    <div class="container">
        
        <!-- Image Carousel -->
        <?php if(count($all_images) > 0): ?>
        <div class="row justify-content-center mb-5">
            <div class="col-lg-11">
                <div class="card border-0 shadow-lg p-3 bg-white" style="border-radius: 24px;">
                    <div id="projectCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="1500" style="border-radius: 16px; overflow: hidden; background: var(--bg-light); box-shadow: inset 0 0 20px rgba(0,0,0,0.05);">
                        <div class="carousel-inner">
                            <?php foreach($all_images as $index => $img): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="assets/images/projects/<?= htmlspecialchars($img) ?>" class="d-block w-100" alt="Project Image" style="max-height: 650px; object-fit: contain; padding: 20px;">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Project Information -->
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="row g-5">
                    
                    <!-- Left Column: Description -->
                    <div class="col-lg-8">
                        <div class="bg-white p-5 h-100 shadow-sm" style="border-radius: 24px; border: 1px solid rgba(0,27,71,0.03);">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; color: var(--primary-color);">
                                    <i class="bi bi-file-earmark-text-fill fs-4"></i>
                                </div>
                                <h3 class="fw-bolder mb-0" style="color: var(--primary-color);">Project Overview</h3>
                            </div>
                            <div style="height: 4px; width: 60px; background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); border-radius: 4px; margin-bottom: 30px;"></div>
                            
                            <div class="text-muted fs-5 lh-lg" style="color: var(--text-dark) !important; font-weight: 400; opacity: 0.85;">
                                <?= nl2br(htmlspecialchars($project['description'])) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Meta Info -->
                    <div class="col-lg-4">
                        <div class="d-flex flex-column gap-4 h-100">
                            
                            <!-- Tech Stack Card -->
                            <div class="bg-white p-4 shadow-sm" style="border-radius: 24px; border: 1px solid rgba(0,27,71,0.03); flex: 1;">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-code-square fs-5 me-2" style="color: var(--accent-color);"></i>
                                    <h5 class="fw-bold mb-0" style="color: var(--primary-color);">Technologies</h5>
                                </div>
                                <?php 
                                $tech_array = !empty($project['tech_stack']) ? explode(',', $project['tech_stack']) : ['N/A'];
                                ?>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <?php foreach($tech_array as $tech): ?>
                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem; font-weight: 500;">
                                            <?= htmlspecialchars(trim($tech)) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Client Card -->
                            <div class="bg-white p-4 shadow-sm" style="border-radius: 24px; border: 1px solid rgba(0,27,71,0.03); flex: 1;">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-person-badge-fill fs-5 me-2" style="color: var(--accent-color);"></i>
                                    <h5 class="fw-bold mb-0" style="color: var(--primary-color);">Client</h5>
                                </div>
                                <p class="fs-5 fw-semibold text-dark mt-2 mb-0">
                                    <?= !empty($project['client']) ? htmlspecialchars($project['client']) : 'Personal Project' ?>
                                </p>
                            </div>

                            <!-- Visit Link -->
                            <?php if(!empty($project['project_link'])): ?>
                            <a href="<?= htmlspecialchars($project['project_link']) ?>" target="_blank" class="btn btn-primary-custom w-100 py-3 shadow-lg d-flex align-items-center justify-content-center" style="border-radius: 16px; font-size: 1.1rem;">
                                <span>Visit Live Project</span>
                                <i class="bi bi-arrow-up-right-circle-fill ms-3 fs-4"></i>
                            </a>
                            <?php endif; ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
