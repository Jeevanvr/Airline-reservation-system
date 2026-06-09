<?php
include("db.php");

if (!isset($_GET['rid'])) {
    header("Location: index.php");
    exit;
}
$rid = intval($_GET['rid']);

$sql = "SELECT R.R_ID, P.P_name, F.F_Source, F.F_Dest, Py.Py_Amt, Py.Py_mode, R.R_SNo, airplane.A_Model
        FROM Reservation R
        JOIN Passenger P ON R.P_ID=P.P_ID
        JOIN Flight F ON R.F_ID=F.F_ID
        LEFT JOIN airplane ON F.A_ID = airplane.A_ID
        JOIN Payment Py ON R.R_ID=Py.R_ID
        WHERE R.R_ID=$rid";
$result  = mysqli_query($conn, $sql);
$details = mysqli_fetch_assoc($result);
if (!$details) {
    die("Reservation not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>BookMyFlight - Booking Confirmed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light text-center mt-5">
<div class="container">
  <h2>Booking Confirmed ✅</h2>
  <p>Reservation ID: <b><?php echo htmlspecialchars($details['R_ID']); ?></b></p>
  <p>Passenger: <?php echo htmlspecialchars($details['P_name']); ?></p>
  <p>Route: <?php echo htmlspecialchars($details['F_Source']); ?> → <?php echo htmlspecialchars($details['F_Dest']); ?></p>
  <p>Seat: <?php echo htmlspecialchars($details['R_SNo']); ?></p>
  <p>Airplane Model: <?php echo htmlspecialchars($details['A_Model'] ?? '—'); ?></p>
  <p>Amount Paid: ₹<?php echo number_format($details['Py_Amt'], 2); ?> (<?php echo htmlspecialchars($details['Py_mode']); ?>)</p>
  <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
</div>
<footer class="text-center mt-5 mb-3 text-muted">
  © 2025 BookMyFlight | Designed by Jeevan V R
</footer>
</body>
</html>
