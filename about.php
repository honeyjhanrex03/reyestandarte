<?php include 'includes/header.php'; ?>

<header class="page-header">
    <div class="container">
        <h1>About Me</h1>
        <p>Get to know my background, education, and experiences.</p>
    </div>
</header>

<section class="section-padding bg-white">
    <div class="container">
        <div class="row g-5 align-items-center mb-5">
            <div class="col-lg-6">
                <!-- Using a placeholder for about me image -->
                <div class="position-relative">
                    <div style="position: absolute; top: 10px; left: -10px; width: 100%; height: 100%; background: var(--primary-color); border-radius: 20px; z-index: 0;"></div>
                    <img src="assets/images/rey.jpg" class="img-fluid position-relative shadow-lg" style="border-radius: 20px; z-index: 1; width: 100%; height: auto; object-fit: cover;" alt="Reynaldo">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <span class="text-uppercase fw-bold" style="color: var(--accent-color); letter-spacing: 2px;">My Story</span>
                <h2 class="mb-4">Motivated & Detail-Oriented Virtual Assistant</h2>
                <p class="text-muted lh-lg fs-5"><?= nl2br(htmlspecialchars($settings['about_text'] ?? '')) ?></p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <span class="badge bg-light text-dark border p-3 rounded-pill fs-6"><i class="bi bi-check-circle-fill text-success me-2"></i>Professionalism</span>
                    <span class="badge bg-light text-dark border p-3 rounded-pill fs-6"><i class="bi bi-check-circle-fill text-success me-2"></i>Time Management</span>
                    <span class="badge bg-light text-dark border p-3 rounded-pill fs-6"><i class="bi bi-check-circle-fill text-success me-2"></i>Adaptability</span>
                </div>
                <div class="mt-5 d-flex gap-3">
                    <a href="resume/Estandarte_CV.pdf" target="_blank" class="btn btn-primary-custom d-inline-flex align-items-center">
                        <i class="bi bi-file-earmark-pdf-fill me-2 fs-5"></i> View Resume
                    </a>
                    <a href="resume/Estandarte_CV.pdf" download class="btn btn-outline-custom d-inline-flex align-items-center">
                        <i class="bi bi-download me-2 fs-5"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-slate">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <h3 class="mb-5"><i class="bi bi-mortarboard-fill me-3 text-primary" style="color: var(--primary-color) !important;"></i>Education</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <h4 class="fw-bold">BS Information Systems</h4>
                        <span class="text-muted fw-bold d-block mb-2">Davao del Norte State College | 2024 - Present</span>
                        <p class="text-muted">Pursuing a degree focused on integrating technology with business processes.</p>
                    </div>
                    <div class="timeline-item">
                        <h4 class="fw-bold">Information and Communication Technology (ICT)</h4>
                        <span class="text-muted fw-bold d-block mb-2">Alejandra L. Navarro National High School | 2022 - 2024</span>
                        <p class="text-muted">Specialized in ICT for Senior High School.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="mb-5"><i class="bi bi-briefcase-fill me-3 text-primary" style="color: var(--primary-color) !important;"></i>Experience & Training</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <h4 class="fw-bold">Part-Time Virtual Assistant</h4>
                        <span class="text-muted fw-bold d-block mb-2">Agile Service Group | Jun - Aug 2024</span>
                        <p class="text-muted">Created and added deals, demonstrating accurate data entry skills.</p>
                    </div>
                    <div class="timeline-item">
                        <h4 class="fw-bold">On-the-Job Training (OJT)</h4>
                        <span class="text-muted fw-bold d-block mb-2">BPAROLE Office – Panabo | Mar 2024</span>
                        <p class="text-muted">80-Hour Completion working alongside Supervisors and Staff Assistants.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
