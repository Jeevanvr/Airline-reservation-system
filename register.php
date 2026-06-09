<?php
include("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // Hash for security

    // Check if username already exists
    $check = mysqli_query($conn, "SELECT * FROM Users WHERE Username='$username'");
    if ($check && mysqli_num_rows($check) > 0) {
        $error = "⚠️ Username already taken. Please choose another.";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO Users (FullName, Username, Email, Password)
                VALUES ('$fullname','$username','$email','$password')";
        
        if (mysqli_query($conn, $sql)) {
            // Get the auto-generated User ID
            $uid = mysqli_insert_id($conn);

            // Store user info in session
            $_SESSION['username'] = $username;
            $_SESSION['user'] = $fullname;
            $_SESSION['uid'] = $uid;
            $_SESSION['email'] = $email;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>BookMyFlight - Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-bg">

<!-- Registration Page -->
<div class="container mt-5">
  <div class="login-card mx-auto p-4 shadow-lg">
    <h3 class="text-center mb-4 text-primary">📝 Create an Account</h3>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
      <input type="text" name="fullname" class="form-control mb-3" placeholder="Full Name" required>
      <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
      <input type="email" name="email" class="form-control mb-3" placeholder="Email Address" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

      <button class="btn btn-success w-100">Register</button>
    </form>

    <p class="text-center mt-3">
      Already have an account? <a href="login.php">Login here</a>
    </p>
  </div>
</div>

<footer class="text-center mt-4 mb-3 text-muted">
  © 2025 BookMyFlight | Designed by Jeevan V R
</footer>

</body>
</html>
