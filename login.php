<?php
include("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM Users WHERE Username='$username' AND Password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $user['Username'];
        $_SESSION['user'] = $user['FullName'];
        $_SESSION['uid'] = $user['U_ID'];
        $_SESSION['email'] = $user['Email'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BookMyFlight - Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Centered Website Title -->
  <div class="site-title text-center mt-5">
    <a href="index.php" class="text-decoration-none text-primary fw-bold" style="font-size:1.8rem;">✈️ BookMyFlight</a>
  </div>

  <!-- Login Box -->
  <div class="login-card mx-auto mt-4 p-4 shadow-lg">
    <h3 class="text-center mb-4 text-primary">🔐 User Login</h3>

    <?php if (isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

    <form method="POST">
      <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
      <button class="btn btn-primary w-100">Login</button>
    </form>

    <p class="footer-text text-center mt-3">
      Don’t have an account? <a href="register.php">Create one</a>
    </p>
  </div>

  <footer class="text-center mt-4 mb-3 text-muted">
    © 2025 BookMyFlight | Designed by Jeevan V R
  </footer>

</body>
</html>
