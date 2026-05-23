<?php 
include 'includes/header.php'; 
$stmt_projects = $pdo->query("SELECT * FROM projects ORDER BY id DESC");
$projects = $stmt_projects->fetchAll();
?>

<header class="page-header">
    <div class="container">
        <h1>Portfolio</h1>
        <p>A showcase of my recent work, school projects, and data entry samples.</p>
    </div>
</header>

<section class="section-padding bg-white">
    <div class="container">
        <?php if (count($projects) > 0): ?>
            <div class="row g-4">
                <?php foreach($projects as $project): ?>
                <div class="col-md-6 col-lg-4">
                    <a href="project_details.php?id=<?= $project['id'] ?>" class="text-decoration-none h-100 d-block">
                        <div class="portfolio-card bg-white h-100" style="border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); transition: transform 0.3s ease;">
                            <div class="portfolio-img-wrapper" style="height: 240px; overflow: hidden; border-radius: 16px 16px 0 0;">
                                <img src="<?= $project['image'] ? 'assets/images/projects/'.htmlspecialchars($project['image']) : 'https://placehold.co/600x400/001B47/FFF?text=Project' ?>" alt="<?= htmlspecialchars($project['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="portfolio-content p-4">
                                <h5 class="fw-bold mb-3" style="color: #111827; line-height: 1.4; font-size: 1.2rem;"><?= htmlspecialchars($project['title']) ?></h5>
                                <p class="text-muted mb-0 lh-base" style="font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($project['description']) ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-grid-3x3-gap text-muted opacity-25" style="font-size: 6rem;"></i>
                </div>
                <h3 class="text-muted fw-bold">Portfolio is currently empty.</h3>
                <p class="text-muted fs-5">Check back later once the admin uploads new projects!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
