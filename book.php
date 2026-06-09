<?php
include("db.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['fid'])) {
    header("Location: index.php");
    exit;
}

$fid = intval($_GET['fid']);

// Fetch flight + airplane model
$flight_q = mysqli_query(
    $conn,
    "SELECT Flight.*, airplane.A_Model 
     FROM Flight 
     LEFT JOIN airplane ON Flight.A_ID = airplane.A_ID
     WHERE Flight.F_ID='$fid'"
);
$flight = mysqli_fetch_assoc($flight_q);

if (!$flight) {
    die("Flight not found.");
}

$price = $flight['F_Price']; // ticket price

// When the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $age    = intval($_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone  = mysqli_real_escape_string($conn, $_POST['phone']);
    $seat   = mysqli_real_escape_string($conn, $_POST['seat']);
    $mode   = mysqli_real_escape_string($conn, $_POST['mode']);

    // Insert passenger info
    mysqli_query($conn, "INSERT INTO Passenger (P_name, P_age, P_gender, P_number)
                         VALUES ('$name', '$age', '$gender', '$phone')");
    $pid = mysqli_insert_id($conn);

    // Get logged-in user's ID
    $uid = $_SESSION['uid'];

    // Insert reservation
    mysqli_query($conn, "INSERT INTO Reservation (F_Date, R_SNo, R_Status, P_ID, F_ID, U_ID)
                         VALUES (CURDATE(), '$seat', 'Pending', '$pid', '$fid', '$uid')");
    $rid = mysqli_insert_id($conn);

    // Insert payment info
    mysqli_query($conn, "INSERT INTO Payment (Py_Amt, Py_mode, Py_Date, R_ID)
                         VALUES ('$price', '$mode', CURDATE(), '$rid')");

    // Update reservation status
    mysqli_query($conn, "UPDATE Reservation SET R_Status='Confirmed' WHERE R_ID='$rid'");

    header("Location: confirm.php?rid=$rid");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>BookMyFlight - Book Flight</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary fs-4" href="index.php">✈️ BookMyFlight</a>
    <div class="ms-auto">
      <a href="dashboard.php" class="btn btn-outline-primary me-2">📋 My Bookings</a>
      <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h3 class="text-center mb-4">Book Flight</h3>

  <div class="card p-4 shadow-sm">
    <h5>Flight Details:</h5>
    <p><b>From:</b> <?php echo htmlspecialchars($flight['F_Source']); ?>  
       <b>→</b> <?php echo htmlspecialchars($flight['F_Dest']); ?></p>
    <p><b>Date:</b> <?php echo htmlspecialchars($flight['Date']); ?>  
       <b>Time:</b> <?php echo htmlspecialchars($flight['Time']); ?></p>
    <p><b>Airplane Model:</b> <?php echo htmlspecialchars($flight['A_Model'] ?? '—'); ?></p>
    <p><b>Ticket Price:</b> ₹<?php echo number_format($price, 2); ?></p>
  </div>

  <form method="POST" class="mt-4" onsubmit="return validateBookingForm() && confirmBooking()">
    <h5>Passenger Details:</h5>
    <input class="form-control mb-2" name="name" placeholder="Full Name" required>
    <input class="form-control mb-2" name="age" placeholder="Age" required>
    <input class="form-control mb-2" name="gender" placeholder="Gender (M/F)" required>
    <input class="form-control mb-2" name="phone" placeholder="Phone Number" required>

    <h5 class="mt-4">Booking Details:</h5>
    <input class="form-control mb-2" name="seat" placeholder="Seat No." required>

    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($price); ?>">

    <select class="form-control mb-2" name="mode" required>
      <option value="">Select Payment Mode</option>
      <option>Credit Card</option>
      <option>UPI</option>
      <option>Net Banking</option>
      <option>Debit Card</option>
    </select>

    <button class="btn btn-success mt-3">Confirm Booking</button>
  </form>
</div>

<footer class="text-center mt-5 mb-3 text-muted">
  © 2025 BookMyFlight | Designed by Jeevan V R
</footer>
</body>
</html>
