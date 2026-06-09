<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BookMyFlight - Customer Care</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary fs-4" href="index.php">
      ✈️ BookMyFlight
    </a>
    <div class="ms-auto d-flex align-items-center">
      <a href="customer_care.php" class="btn btn-outline-info me-2">
        ☎️ Customer Care
      </a>

      <?php if (isset($_SESSION['username'])): ?>
        <a href="dashboard.php" class="btn btn-outline-success me-2">📋 My Bookings</a>
        <span class="me-3 fw-semibold text-success">Welcome, <?php echo htmlspecialchars($_SESSION['user'] ?? $_SESSION['username']); ?> 👋</span>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-primary">👤 Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Main Section -->
<div class="container mt-5">
  <div class="text-center mb-4">
    <h2 class="text-primary">📞 Customer Care</h2>
    <p class="text-muted">We’re here to help you 24/7! Get in touch with us below.</p>
  </div>

  <div class="row g-4">
    <!-- Contact Info -->
    <div class="col-md-6">
      <div class="card p-4">
        <h5 class="text-secondary mb-3">Contact Information</h5>
        <p>📧 <b>Email:</b> support@bookmyflight.com</p>
        <p>📞 <b>Helpline:</b> +91 98765 43210</p>
        <p>🏢 <b>Address:</b><br>
        BookMyFlight Pvt. Ltd.<br>
        MG Road, Bengaluru, Karnataka – 560001</p>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="col-md-6">
      <div class="card p-4">
        <h5 class="text-secondary mb-3">Send Us a Message</h5>
        <!-- If you plan to persist messages, change method to POST and action to a handler -->
        <form>
          <input type="text" class="form-control mb-3" placeholder="Your Name" required>
          <input type="email" class="form-control mb-3" placeholder="Your Email" required>
          <textarea class="form-control mb-3" rows="4" placeholder="Your Message" required></textarea>
          <button class="btn btn-primary w-100">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <!-- FAQ Section -->
  <div class="card mt-5 p-4">
    <h5 class="text-secondary mb-3">🧐 Frequently Asked Questions</h5>
    <p><b>Q:</b> How do I cancel my ticket?<br>
       <b>A:</b> You can cancel your booking from your Dashboard under "My Bookings".</p>
    <hr>
    <p><b>Q:</b> Can I change my travel date?<br>
       <b>A:</b> Please contact customer care at least 24 hours before your flight.</p>
    <hr>
    <p><b>Q:</b> How do I get a refund?<br>
       <b>A:</b> Refunds are processed to the same payment mode within 7 working days.</p>
  </div>
</div>

<footer class="text-center mt-5 mb-3 text-muted">
  © 2025 BookMyFlight | Designed by Jeevan V R
</footer>

</body>
</html>
