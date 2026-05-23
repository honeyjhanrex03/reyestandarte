<?php 
include 'includes/header.php'; 
$stmt_services = $pdo->query("SELECT * FROM services");
$services = $stmt_services->fetchAll();
?>

<header class="page-header">
    <div class="container">
        <h1>My Services</h1>
        <p>What I bring to the table. Solutions aligned with your needs.</p>
    </div>
</header>

<section class="section-padding bg-slate">
    <div class="container">
        <div class="row g-4">
            <?php foreach($services as $service): ?>
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi <?= htmlspecialchars($service['icon']) ?>"></i>
                    </div>
                    <h3 class="fw-bold mb-3"><?= htmlspecialchars($service['title']) ?></h3>
                    <p class="text-muted fs-5 lh-base"><?= htmlspecialchars($service['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-5 text-center bg-white p-5 rounded-4 shadow-sm border">
            <h3 class="mb-3">Need a custom service?</h3>
            <p class="text-muted mb-4 fs-5">I am highly adaptable and capable of learning new tools rapidly to suit your business requirements.</p>
            <a href="contact" class="btn btn-primary-custom">Let's Discuss</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
