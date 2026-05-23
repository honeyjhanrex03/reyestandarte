<?php include 'includes/header.php'; ?>

<section class="hero">
    <div class="container">
        <div class="row align-items-center flex-column-reverse flex-lg-row">
            <div class="col-lg-6 hero-content">
                <span class="hero-subtitle">Welcome to my portfolio</span>
                <h1 class="hero-title">
                    <span class="d-block mb-2" style="font-size: 2.2rem; color: var(--text-dark);">Hi, I'm Reynaldo Estandarte Jr.</span>
                    <?= htmlspecialchars($settings['hero_title'] ?? 'Virtual Assistant') ?>
                </h1>
                <p class="hero-tagline"><?= htmlspecialchars($settings['hero_tagline'] ?? '') ?></p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start mt-4">
                    <a href="portfolio" class="btn btn-primary-custom">Explore My Work</a>
                    <a href="contact" class="btn btn-outline-custom">Contact Me</a>
                </div>
            </div>
            <div class="col-lg-6 mb-5 mb-lg-0 text-center">
                <div class="position-relative d-inline-block">
                    <!-- Complex Image blob -->
                    <img src="assets/images/rey.jpg" alt="Reynaldo Estandarte Jr." class="img-blob">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mini feature section on homepage -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4">Dedicated to providing reliable support & delivering quality results.</h2>
                <p class="text-muted fs-5 mb-5">With a background in Information Systems and diverse work experiences, I bring a unique blend of technical aptitude and administrative excellence.</p>
                <a href="about" class="btn btn-outline-custom">Learn More About Me</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
