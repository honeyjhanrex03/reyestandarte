<?php 
include 'includes/header.php'; 
$stmt_certs = $pdo->query("SELECT * FROM certificates ORDER BY id DESC");
$certificates = $stmt_certs->fetchAll();
?>

<header class="page-header">
    <div class="container">
        <h1>Certifications</h1>
        <p>Continuous learning and professional development achievements.</p>
    </div>
</header>

<section class="section-padding bg-slate">
    <div class="container">
        <div class="row g-5">
            <?php foreach($certificates as $index => $cert): ?>
            <div class="col-lg-6">
                <div class="bg-white rounded-4 shadow-sm border overflow-hidden h-100 d-flex flex-column" style="padding: 24px;">
                    <?php if(!empty($cert['image'])): ?>
                        <div style="height: 300px; cursor: pointer; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 8px; margin-bottom: 24px;" data-bs-toggle="modal" data-bs-target="#certModal<?= $index ?>">
                            <img src="assets/images/certificates/<?= htmlspecialchars($cert['image']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="Certificate">
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="certModal<?= $index ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-header border-0 pb-0 justify-content-between" style="background: rgba(33, 37, 41, 0.95); border-radius: 8px 8px 0 0;">
                                        <h5 class="modal-title text-white"><?= htmlspecialchars($cert['title']) ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0 text-center" style="background: rgba(33, 37, 41, 0.95); border-radius: 0 0 8px 8px;">
                                        <img src="assets/images/certificates/<?= htmlspecialchars($cert['image']) ?>" class="img-fluid" style="max-height: 85vh;" alt="Certificate">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex flex-column flex-grow-1">
                        <h3 class="fw-bold mb-3" style="font-size: 1.5rem; color: #111827;"><?= htmlspecialchars($cert['title']) ?></h3>
                        
                        <div class="mb-3 text-muted" style="font-size: 0.95rem;">
                            <?php if(!empty($cert['issue_month']) || !empty($cert['issue_year'])): ?>
                                <?= htmlspecialchars($cert['issue_month']) ?> <?= htmlspecialchars($cert['issue_year']) ?>
                            <?php endif; ?>
                        </div>

                        <?php if(!empty($cert['issued_by'])): ?>
                            <div class="fw-bold mb-3" style="color: #111827; font-size: 1.05rem;">
                                <?= htmlspecialchars($cert['issued_by']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($cert['description'])): ?>
                            <p class="text-secondary mb-4" style="line-height: 1.7; font-size: 0.95rem;"><?= nl2br(htmlspecialchars($cert['description'])) ?></p>
                        <?php endif; ?>
                        
                        <?php if(!empty($cert['keywords'])): 
                            $keywords = explode(',', $cert['keywords']);
                        ?>
                            <div class="mt-auto">
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach($keywords as $keyword): ?>
                                        <?php if(trim($keyword)): ?>
                                            <span class="badge bg-dark text-white px-3 py-2 rounded-pill" style="font-weight: 500; font-size: 0.85rem;"><?= htmlspecialchars(trim($keyword)) ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($certificates)): ?>
                <div class="col-12 text-center py-5">
                    <h3 class="text-muted">Certifications are being updated. Check back soon!</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
