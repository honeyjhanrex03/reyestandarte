<?php 
require_once 'includes/db.php';

// Handle Form Submission BEFORE header output
$msg_success = false;
$msg_error = false;

function sendBrevoEmail($toEmail, $toName, $subject, $htmlContent) {
    require_once 'config.php';
    $apiKey = BREVO_API_KEY;
    
    $data = [
        'sender' => ['name' => 'Reynaldo Estandarte', 'email' => 'reynaldoestandarte8@gmail.com'],
        'to' => [['email' => $toEmail, 'name' => $toName]],
        'subject' => $subject,
        'htmlContent' => $htmlContent
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $headers = [
        'accept: application/json',
        'api-key: ' . $apiKey,
        'content-type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $insert = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if ($insert->execute([$name, $email, $subject, $message])) {
        $msg_success = true;

        // Send Professional Auto-Reply to the User
        $userHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>
            <div style='background-color: #001B47; padding: 20px; text-align: center;'>
                <h1 style='color: #ffffff; margin: 0; font-size: 24px;'>Message Received</h1>
            </div>
            <div style='padding: 30px; background-color: #ffffff; border: 1px solid #e0e0e0; border-top: none;'>
                <h2 style='color: #001B47; margin-top: 0;'>Hello {$name},</h2>
                <p style='line-height: 1.6;'>Thank you for reaching out! This is an automated confirmation that I have successfully received your message regarding <strong>\"{$subject}\"</strong>.</p>
                <p style='line-height: 1.6;'>I will review your inquiry and get back to you at this email address as soon as possible.</p>
                <br>
                <p style='margin-bottom: 5px; color: #666;'>Best regards,</p>
                <p style='margin: 0; font-size: 18px; color: #001B47;'><strong>Reynaldo Estandarte Jr.</strong></p>
                <p style='margin: 0; color: #666;'>System Analyst & Web Developer</p>
            </div>
            <div style='text-align: center; padding: 20px; font-size: 12px; color: #999;'>
                &copy; " . date('Y') . " Reynaldo Estandarte. All rights reserved.
            </div>
        </div>
        ";
        sendBrevoEmail($email, $name, "Thank you for your message: " . $subject, $userHtml);

        // Send Notification to Reynaldo
        $adminHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>
            <h2 style='color: #001B47; border-bottom: 2px solid #001B47; padding-bottom: 10px;'>New Portfolio Message</h2>
            <p>You have received a new message from your portfolio contact form.</p>
            <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
                <tr><td style='padding: 12px; border-bottom: 1px solid #eee; width: 100px;'><strong>Name:</strong></td><td style='padding: 12px; border-bottom: 1px solid #eee;'>{$name}</td></tr>
                <tr><td style='padding: 12px; border-bottom: 1px solid #eee;'><strong>Email:</strong></td><td style='padding: 12px; border-bottom: 1px solid #eee;'>{$email}</td></tr>
                <tr><td style='padding: 12px; border-bottom: 1px solid #eee;'><strong>Subject:</strong></td><td style='padding: 12px; border-bottom: 1px solid #eee;'>{$subject}</td></tr>
            </table>
            <h3 style='margin-top: 30px; color: #001B47;'>Message Content:</h3>
            <div style='margin-top: 10px; padding: 20px; background-color: #f9f9f9; border-left: 4px solid #001B47; font-size: 15px; line-height: 1.6;'>
                <p style='margin: 0; white-space: pre-wrap;'>{$message}</p>
            </div>
            <p style='margin-top: 40px;'><a href='http://localhost/rey/admin/messages.php' style='background-color: #001B47; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Open Admin Dashboard</a></p>
        </div>
        ";
        sendBrevoEmail('reynaldoestandarte8@gmail.com', 'Reynaldo Estandarte', "New Message from " . $name . ": " . $subject, $adminHtml);

    } else {
        $msg_error = true;
    }
}

include 'includes/header.php'; 
?>

<header class="page-header">
    <div class="container">
        <h1>Contact Me</h1>
        <p>Ready to start your next project? Drop me a message.</p>
    </div>
</header>

<section class="section-padding bg-slate">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="pe-lg-4">
                    <h2 class="mb-4">Get In Touch</h2>
                    <p class="text-muted fs-5 mb-5 lh-lg">Whether you have a question, a project proposal, or just want to say hi, I'll try my best to get back to you!</p>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Location</h5>
                            <p class="text-muted fs-6 mb-0"><?= htmlspecialchars($settings['location'] ?? '') ?></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Email</h5>
                            <p class="text-muted fs-6 mb-0"><a href="mailto:<?= htmlspecialchars($settings['email'] ?? '') ?>" class="text-decoration-none text-muted"><?= htmlspecialchars($settings['email'] ?? '') ?></a></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Phone</h5>
                            <p class="text-muted fs-6 mb-0"><a href="tel:<?= htmlspecialchars($settings['phone'] ?? '') ?>" class="text-decoration-none text-muted"><?= htmlspecialchars($settings['phone'] ?? '') ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <h3 class="mb-4">Send a Message</h3>
                        <form method="POST" action="contact">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold">Your Name</label>
                                    <input type="text" name="name" class="form-control form-control-lg bg-light" required placeholder="John Doe">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold">Your Email</label>
                                    <input type="email" name="email" class="form-control form-control-lg bg-light" required placeholder="john@example.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-bold">Subject</label>
                                    <input type="text" name="subject" class="form-control form-control-lg bg-light" required placeholder="Project Inquiry">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted fw-bold">Message</label>
                                    <textarea name="message" class="form-control form-control-lg bg-light" rows="6" required placeholder="Hello Reynaldo..."></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" name="submit_contact" class="btn btn-primary-custom btn-lg w-100 py-3"><i class="bi bi-send-fill me-2"></i>Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if($msg_success): ?>
    Swal.fire({
        icon: 'success',
        title: 'Message Sent!',
        text: 'Thank you for reaching out. Please check your email for a confirmation receipt.',
        confirmButtonColor: '#001B47'
    });
    <?php endif; ?>

    <?php if($msg_error): ?>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong while sending your message. Please try again later.',
        confirmButtonColor: '#ef4444'
    });
    <?php endif; ?>
</script>

<?php include 'includes/footer.php'; ?>
