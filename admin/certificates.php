<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Handle Add Certificate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_certificate'])) {
    $title = $_POST['title'];
    $issued_by = $_POST['issued_by'];
    $issue_month = $_POST['issue_month'];
    $issue_year = $_POST['issue_year'];
    $keywords = $_POST['keywords'];
    $description = $_POST['description'];
    
    // Image Upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/certificates/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid('cert_') . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $new_filename;
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO certificates (title, issued_by, issue_month, issue_year, keywords, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $issued_by, $issue_month, $issue_year, $keywords, $description, $image]);
    
    header('Location: certificates?msg=added');
    exit;
}

// Handle Edit Certificate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_certificate'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $issued_by = $_POST['issued_by'];
    $issue_month = $_POST['issue_month'];
    $issue_year = $_POST['issue_year'];
    $keywords = $_POST['keywords'];
    $description = $_POST['description'];
    
    // Image Upload
    $image = $_POST['existing_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/certificates/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid('cert_') . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if (!empty($image) && file_exists("../assets/images/certificates/" . $image)) {
                unlink("../assets/images/certificates/" . $image);
            }
            $image = $new_filename;
        }
    }
    
    $stmt = $pdo->prepare("UPDATE certificates SET title = ?, issued_by = ?, issue_month = ?, issue_year = ?, keywords = ?, description = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $issued_by, $issue_month, $issue_year, $keywords, $description, $image, $id]);
    
    header('Location: certificates?msg=updated');
    exit;
}

// Handle Delete Certificate
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("SELECT image FROM certificates WHERE id = ?");
    $stmt->execute([$id]);
    $cert = $stmt->fetch();
    
    if ($cert && !empty($cert['image']) && file_exists("../assets/images/certificates/" . $cert['image'])) {
        unlink("../assets/images/certificates/" . $cert['image']);
    }

    $stmt = $pdo->prepare("DELETE FROM certificates WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: certificates?msg=deleted');
    exit;
}

// Fetch all certificates
$stmt = $pdo->query("SELECT * FROM certificates ORDER BY id DESC");
$certificates = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Certificates - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?= time() ?>">
    <style>
        .custom-form-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #eef0f4;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            margin-bottom: 40px;
        }
        .form-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.95rem;
            background-color: #f9fafb;
            color: #374151;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .help-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 6px;
        }
        .btn-black {
            background-color: #000000;
            color: #ffffff;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-black:hover {
            background-color: #1f2937;
            transform: translateY(-1px);
        }
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div id="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="toggle-btn me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h4>Certificates</h4>
            </div>
            <div class="d-none d-md-block">
                <img src="https://ui-avatars.com/api/?name=Reynaldo+Estandarte&background=3b82f6&color=fff&bold=true" alt="Admin" class="rounded-circle" width="45">
            </div>
        </div>

        <h1 class="page-title">Manage Certificates</h1>

        <div class="custom-form-card">
            <h5 class="form-section-title">Add Certificate</h5>
            
            <form method="POST" action="certificates" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">Issued By</label>
                        <input type="text" name="issued_by" class="form-control">
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <label class="form-label">Month</label>
                        <select name="issue_month" class="form-select">
                            <option value="">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Year</label>
                        <input type="number" name="issue_year" class="form-control" value="<?= date('Y') ?>">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <label class="form-label">Certificate Image</label>
                        <input type="file" name="image" class="form-control" accept=".png,.jpg,.jpeg">
                        <div class="help-text">JPG/PNG only, max 2MB</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Keywords (comma-separated)</label>
                        <input type="text" name="keywords" class="form-control" placeholder="e.g., .NET Framework, C# Programming, Enterprise Development">
                        <div class="help-text">Separate keywords with commas</div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>

                <button type="submit" name="add_certificate" class="btn-black">Add Certificate</button>
            </form>
        </div>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Issued By</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($certificates as $c): ?>
                        <tr>
                            <td>
                                <?php if(!empty($c['image'])): ?>
                                    <img src="../assets/images/certificates/<?= htmlspecialchars($c['image']) ?>" width="80" height="60" style="object-fit:cover; border-radius: 6px;" alt="Cert">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 80px; height: 60px;"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($c['title']) ?></td>
                            <td><?= htmlspecialchars($c['issued_by']) ?></td>
                            <td><?= htmlspecialchars($c['issue_month'] . ' ' . $c['issue_year']) ?></td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-light text-primary border-0 rounded-circle p-2" data-bs-toggle="modal" data-bs-target="#editCertModal<?= $c['id'] ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-light text-danger border-0 rounded-circle p-2 ms-1" onclick="confirmDelete(<?= $c['id'] ?>)">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Certificate Modal -->
                        <div class="modal fade" id="editCertModal<?= $c['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold fs-4">Edit Certificate</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="certificates" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($c['image']) ?>">
                                            
                                            <div class="mb-4">
                                                <label class="form-label text-muted fw-bold">Title *</label>
                                                <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($c['title']) ?>">
                                            </div>

                                            <div class="row mb-4">
                                                <div class="col-md-6 mb-4 mb-md-0">
                                                    <label class="form-label text-muted fw-bold">Issued By</label>
                                                    <input type="text" name="issued_by" class="form-control" value="<?= htmlspecialchars($c['issued_by']) ?>">
                                                </div>
                                                <div class="col-md-3 mb-4 mb-md-0">
                                                    <label class="form-label text-muted fw-bold">Month</label>
                                                    <select name="issue_month" class="form-select">
                                                        <option value="">Select Month</option>
                                                        <?php
                                                        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                                                        foreach($months as $m) {
                                                            $sel = ($c['issue_month'] == $m) ? 'selected' : '';
                                                            echo "<option value=\"$m\" $sel>$m</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label text-muted fw-bold">Year</label>
                                                    <input type="number" name="issue_year" class="form-control" value="<?= htmlspecialchars($c['issue_year']) ?>">
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <div class="col-md-6 mb-4 mb-md-0">
                                                    <label class="form-label text-muted fw-bold">Certificate Image</label>
                                                    <input type="file" name="image" class="form-control" accept=".png,.jpg,.jpeg">
                                                    <?php if(!empty($c['image'])): ?>
                                                        <small class="text-muted d-block mt-1">Current: <?= htmlspecialchars($c['image']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted fw-bold">Keywords (comma-separated)</label>
                                                    <input type="text" name="keywords" class="form-control" value="<?= htmlspecialchars($c['keywords']) ?>">
                                                </div>
                                            </div>

                                            <div class="mb-5">
                                                <label class="form-label text-muted fw-bold">Description</label>
                                                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($c['description']) ?></textarea>
                                            </div>

                                            <button type="submit" name="edit_certificate" class="btn-accent w-100 py-3 fs-5">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($certificates)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No certificates added yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This certificate will be deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'certificates?delete=' + id;
                }
            })
        }

        <?php if(isset($_GET['msg'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Certificate has been <?= $_GET['msg'] ?>.',
            confirmButtonColor: '#3b82f6',
            timer: 2000,
            showConfirmButton: false
        });
        <?php endif; ?>
    </script>
</body>
</html>
