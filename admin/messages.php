<?php
require_once 'includes/auth.php';
require_once '../includes/db.php';

// Handle Delete Message
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: messages?msg=deleted');
    exit;
}

// Mark as read when viewing
if (isset($_GET['view'])) {
    $id = $_GET['view'];
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
    
    // Redirect to self to clear the view param and prevent re-running
    header('Location: messages');
    exit;
}

// Fetch all messages
$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?= time() ?>">
</head>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div id="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="toggle-btn me-3" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h4>Inbox</h4>
            </div>
            <div class="d-none d-md-block">
                <img src="https://ui-avatars.com/api/?name=Reynaldo+Estandarte&background=3b82f6&color=fff&bold=true" alt="Admin" class="rounded-circle" width="45">
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0 fw-bold">Recent Messages</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Date Received</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($messages as $m): ?>
                        <tr class="<?= !$m['is_read'] ? 'table-primary bg-opacity-10' : '' ?>">
                            <td>
                                <?php if(!$m['is_read']): ?>
                                    <span class="badge bg-primary rounded-pill">New</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border rounded-pill">Read</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($m['name']) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($m['email']) ?></div>
                            </td>
                            <td class="fw-medium text-dark"><?= htmlspecialchars($m['subject']) ?></td>
                            <td class="text-muted"><?= date('M d, Y g:i A', strtotime($m['created_at'])) ?></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light text-primary border-0 rounded-circle p-2" onclick="viewMessage('<?= htmlspecialchars(addslashes($m['name'])) ?>', '<?= htmlspecialchars(addslashes($m['email'])) ?>', '<?= htmlspecialchars(addslashes($m['subject'])) ?>', '<?= htmlspecialchars(addslashes(str_replace(["\r", "\n"], ["", "\\n"], $m['message']))) ?>', <?= $m['id'] ?>)">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-light text-danger border-0 rounded-circle p-2 ms-2" onclick="confirmDelete(<?= $m['id'] ?>)">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($messages)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Your inbox is empty.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View Message Modal -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold fs-4">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" id="msgName">Name</h5>
                            <a href="#" id="msgEmail" class="text-muted text-decoration-none small">email@example.com</a>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-dark mb-3">Subject: <span id="msgSubject" class="fw-normal"></span></h6>
                    
                    <div class="p-4 bg-light rounded-4 border">
                        <p id="msgContent" class="mb-0 text-dark lh-lg" style="white-space: pre-wrap;"></p>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <a href="#" id="msgReplyBtn" class="btn-accent text-decoration-none">
                            <i class="bi bi-reply-fill me-2"></i> Reply via Email
                        </a>
                    </div>
                </div>
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
        overlay.addEventListener('click', toggleSidebar);

        function viewMessage(name, email, subject, message, id) {
            document.getElementById('msgName').textContent = name;
            document.getElementById('msgEmail').textContent = email;
            document.getElementById('msgEmail').href = 'mailto:' + email;
            document.getElementById('msgSubject').textContent = subject;
            document.getElementById('msgContent').textContent = message.replace(/\\n/g, '\n');
            document.getElementById('msgReplyBtn').href = 'mailto:' + email + '?subject=Re: ' + encodeURIComponent(subject);
            
            const modal = new bootstrap.Modal(document.getElementById('viewMessageModal'));
            modal.show();
            
            // Mark as read in backend silently
            fetch('messages?view=' + id);
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This message will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'messages?delete=' + id;
                }
            })
        }

        <?php if(isset($_GET['msg'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Message deleted successfully.',
            confirmButtonColor: '#3b82f6',
            timer: 2000,
            showConfirmButton: false
        });
        <?php endif; ?>
    </script>
</body>
</html>
