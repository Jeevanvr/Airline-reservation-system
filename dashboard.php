<?php
include("db.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['uid']; // logged-in user ID

// Fetch bookings + airplane model
$sql = "SELECT 
          R.R_ID, 
          F.F_Source, 
          F.F_Dest, 
          F.Date AS FlightDate, 
          F.Time AS FlightTime, 
          F.A_ID,
          airplane.A_Model,
          R.R_SNo, 
          R.R_Status, 
          Py.Py_Amt, 
          Py.Py_mode
        FROM Reservation R
        JOIN Flight F ON R.F_ID = F.F_ID
        LEFT JOIN airplane ON airplane.A_ID = F.A_ID
        JOIN Payment Py ON R.R_ID = Py.R_ID
        WHERE R.U_ID = '".mysqli_real_escape_string($conn, $uid)."'
        ORDER BY R.R_ID DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>BookMyFlight - My Bookings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary fs-4" href="index.php">✈️ BookMyFlight</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h3 class="text-center mb-4">My Bookings</h3>

  <!-- Show status/cancel message -->
  <?php
  if (isset($_SESSION['msg'])) {
      echo '<div class="alert alert-info text-center">' . htmlspecialchars($_SESSION['msg']) . '</div>';
      unset($_SESSION['msg']);
  }
  ?>

  <div class="card p-4 shadow-sm">
    <table class="table table-bordered table-striped text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>Route</th>
          <th>Date</th>
          <th>Time</th>
          <th>Seat</th>
          <th>Model</th>
          <th>Status</th>
          <th>Amount</th>
          <th>Mode</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {

            $rid = (int)$row['R_ID'];
            $status = $row['R_Status'];

            // Cancel button logic
            if (strtolower($status) === 'confirmed') {
                $actionBtn = '
                    <a href="cancel_booking.php?rid=' . $rid . '" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm(\'Are you sure you want to cancel this booking?\');">
                        Cancel
                    </a>';
            } else {
                $actionBtn = '<span class="text-muted">N/A</span>';
            }

            echo "<tr>
                    <td>".htmlspecialchars($row['R_ID'])."</td>
                    <td>".htmlspecialchars($row['F_Source'])." → ".htmlspecialchars($row['F_Dest'])."</td>
                    <td>".htmlspecialchars($row['FlightDate'])."</td>
                    <td>".htmlspecialchars($row['FlightTime'])."</td>
                    <td>".htmlspecialchars($row['R_SNo'])."</td>
                    <td>".htmlspecialchars($row['A_Model'] ?? '—')."</td>
                    <td>".htmlspecialchars($row['R_Status'])."</td>
                    <td>₹".number_format($row['Py_Amt'], 2)."</td>
                    <td>".htmlspecialchars($row['Py_mode'])."</td>
                    <td>".$actionBtn."</td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='10' class='text-danger'>No bookings found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<footer class="text-center mt-5 mb-3 text-muted">
  © 2025 BookMyFlight | Designed by Jeevan V R
</footer>

</body>
</html>
