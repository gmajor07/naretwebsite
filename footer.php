
        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Get In Touch</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>6230, Dar es Salam, Tanzania</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+255 753 995 084</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>naretfumigation@gmail.com</p>
                        <p class="mb-2"><i class="fa fa-clock-alt me-3"></i>Sun - Fri (8AM - 5PM)</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Quick Links</h5>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">NARET </h5>
                        <div class="row g-2 pt-2">
                            <p>There are many variations of the passages available the majority have suffered alteration in some form by injected humour.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Newsletter</h5>
                        <p>Subscribe Our Newsletter To Get Latest Update And News.</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
<!-- Newsletter Subscribe Button (Triggers Modal) -->
<button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2" data-bs-toggle="modal" data-bs-target="#newsletterModal">
    SignUp
</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">Naret</a>, All Right Reserved.
                    Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="">Home</a>
                        <a href="">Cookies</a>
                        <?php if(isset($_SESSION['admin_id'])): ?>
                            <a href="admin/dashboard.php" class="text-warning" title="Admin Dashboard">
                                <i class="fas fa-user-shield"></i>
                            </a>
                            <a href="admin/logout.php" class="text-danger" title="Logout">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        <?php else: ?>
                            <a href="admin/login.php" class="text-muted" title="Admin Login">
                                <i class="fas fa-lock"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>


<!-- Newsletter Modal -->
<div class="modal fade" id="newsletterModal" tabindex="-1" aria-labelledby="newsletterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title w-100" id="newsletterModalLabel">Subscribe to Newsletter</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <p>Enter your email to receive updates:</p>
          <input type="email" name="newsletter_email" class="form-control mb-3" placeholder="Email address" required>
          <button type="submit" name="subscribe_newsletter" class="btn btn-primary w-100">Subscribe</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Customer Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <form method="POST" action="">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="registerModalLabel">Register as a Customer</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="customerName" class="form-label">Full Name</label>
            <input type="text" name="customer_name" class="form-control" id="customerName" required>
          </div>
          <div class="mb-3">
            <label for="customerPhone" class="form-label">Phone Number</label>
            <input type="tel" name="customer_phone" class="form-control" id="customerPhone" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="register_customer" class="btn btn-primary">Register</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe_newsletter'])) {
    $email = trim($_POST['newsletter_email']);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        require_once 'includes/db.php';
        
        // Check for existing email first
        $check = $conn->prepare("SELECT id FROM newsletter WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $_SESSION['form_message'] = "This email is already subscribed.";
        } else {
            $stmt = $conn->prepare("INSERT INTO newsletter (email) VALUES (?)");
            $stmt->bind_param("s", $email);
            
            if ($stmt->execute()) {
                $_SESSION['form_success'] = true;
                header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent resubmission
                exit();
            } else {
                $_SESSION['form_message'] = "Error subscribing.";
            }
        }
    } else {
        $_SESSION['form_message'] = "Please enter a valid email address.";
    }
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_customer'])) {
    $name = trim($_POST['customer_name']);
    $phone = trim($_POST['customer_phone']);
    
    if ($name && $phone) {
        require_once 'includes/db.php';
        
        // Check for existing phone first
        $check = $conn->prepare("SELECT id FROM customers WHERE phone = ?");
        $check->bind_param("s", $phone);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            $_SESSION['form_message'] = "This phone number is already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO customers (name, phone) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $phone);
            
            if ($stmt->execute()) {
                $_SESSION['form_success'] = true;
                header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent resubmission
                exit();
            } else {
                $_SESSION['form_message'] = "Error registering customer.";
            }
        }
    } else {
        $_SESSION['form_message'] = "Please fill in all fields.";
    }
}
?>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title w-100" id="successModalLabel">Thank You!</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Registration successful!<br>We will reach out to you soon.</p>
      </div>
    </div>
  </div>
</div>


<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title w-100" id="successModalLabel">Success!</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">You're subscribed!<br>We'll keep you updated.</p>
      </div>
    </div>
  </div>
</div>


<?php if (!empty($_SESSION['form_message'])): ?>
    <div class="alert alert-warning">
        <?= htmlspecialchars($_SESSION['form_message']) ?>
        <?php unset($_SESSION['form_message']); ?>
    </div>
<?php endif; ?>

<script>
// In your JS file
// Initialize preview slider
$('.header-preview .header-text-slider').slick({
    autoplay: true,
    autoplaySpeed: 3000,
    arrows: false
});
    </script>

