<?php 
include 'includes/header.php'; 
$stmt_skills = $pdo->query("SELECT * FROM skills ORDER BY level DESC");
$skills = $stmt_skills->fetchAll();
?>

<header class="page-header">
    <div class="container">
        <h1>My Skills</h1>
        <p>A comprehensive overview of my technical proficiencies and capabilities.</p>
    </div>
</header>

<section class="section-padding bg-slate">
    <div class="container py-5">
        <div class="row g-5" style="row-gap: 3.5rem !important;">
            <?php foreach($skills as $skill): ?>
            <div class="col-md-6 col-lg-4">
                <div class="skill-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <?php if(!empty($skill['icon_image'])): ?>
                                <img src="assets/images/skills/<?= htmlspecialchars($skill['icon_image']) ?>" width="28" height="28" style="object-fit:contain; margin-right: 14px;" alt="Icon">
                            <?php elseif(strpos($skill['icon_class'], 'http') === 0): ?>
                                <img src="<?= htmlspecialchars($skill['icon_class']) ?>" width="28" height="28" style="object-fit:contain; margin-right: 14px;" alt="Icon">
                            <?php else: ?>
                                <i class="<?= htmlspecialchars($skill['icon_class']) ?> text-dark" style="font-size: 1.6rem; margin-right: 14px; color: var(--primary-color) !important;"></i>
                            <?php endif; ?>
                            <span class="fw-bold" style="font-size: 1.15rem; color: var(--text-dark); letter-spacing: -0.3px;"><?= htmlspecialchars($skill['name']) ?></span>
                        </div>
                        <span style="font-size: 0.95rem; font-weight: 500; color: var(--text-muted);"><?= htmlspecialchars($skill['level']) ?>%</span>
                    </div>
                    
                    <div class="progress" style="height: 18px; border-radius: 20px; background-color: var(--white); box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                        <div class="progress-bar" role="progressbar" style="width: <?= htmlspecialchars($skill['level']) ?>%; background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); border-radius: 20px;" aria-valuenow="<?= htmlspecialchars($skill['level']) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($skills)): ?>
                <div class="col-12 text-center py-5">
                    <h3 class="text-muted">Skills are being updated. Check back soon!</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}
</style>

<?php include 'includes/footer.php'; ?>
