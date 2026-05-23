    </main>
    <div style="overflow: hidden; width: 100%;">
        <!-- Spacer to protect SVG shape while keeping it inside the wrapper -->
        <div style="min-height: 100px;"></div>
        <footer class="footer position-relative">
            <div class="footer-shape">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#f7fafc" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>
            </div>
            <div class="container text-center pt-5" style="position: relative; z-index: 1;">
            <div class="mb-4">
                <img src="assets/images/logo.png" alt="Reynaldo Estandarte Jr. Logo" height="60">
            </div>
            <div class="social-links mb-4">
                <a href="<?= htmlspecialchars($settings['linkedin_url'] ?? '#') ?>" target="_blank"><i class="bi bi-linkedin"></i></a>
                <a href="mailto:<?= htmlspecialchars($settings['email'] ?? '#') ?>"><i class="bi bi-envelope-fill"></i></a>
                <a href="#"><i class="bi bi-github"></i></a>
            </div>
            <hr class="border-light opacity-25">
            <p class="mb-0 text-white-50 py-3">&copy; <?= date('Y') ?> All Rights Reserved. Crafted with passion.</p>
        </div>
    </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sweet Alert for Form Submission handling if set
        <?php if(isset($msg_success) && $msg_success): ?>
            Swal.fire({
                icon: 'success',
                title: 'Message Sent!',
                text: 'Thank you for reaching out. I will get back to you soon.',
                confirmButtonColor: '#001B47'
            });
        <?php elseif(isset($msg_error) && $msg_error): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong. Please try again later.',
                confirmButtonColor: '#001B47'
            });
        <?php endif; ?>
    </script>
</body>
</html>
