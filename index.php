<?php
include("db.php");
session_start();

// Fetch flights
$search = "";
if (isset($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $sql = "SELECT Flight.*, airplane.A_Model 
          FROM Flight
          LEFT JOIN airplane ON Flight.A_ID = airplane.A_ID
          WHERE F_Source LIKE '%$search%' 
          OR F_Dest LIKE '%$search%' 
          ORDER BY F_ID ASC";
} else {
  $sql = "SELECT Flight.*, airplane.A_Model 
          FROM Flight 
          LEFT JOIN airplane ON Flight.A_ID = airplane.A_ID 
          ORDER BY F_ID ASC";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BookMyFlight</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f2f6ff;
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* HERO SECTION */
    .hero {
      background-image: url('https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1600&q=80');
      background-size: cover;
      background-position: center;
      height: 420px;
      color: white;
      text-align: center;
      padding: 120px 20px;
      border-radius: 0 0 40px 40px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .hero::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      border-radius: 0 0 40px 40px;
    }

    .hero * {
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
    }

    .hero p {
      font-size: 1.2rem;
      margin-top: 10px;
    }

    /* OFFERS */
    .offers img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Search box */
    .search-box {
      width: 60%;
      margin: 25px auto 0;
    }

    /* Flight table container */
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    footer {
      text-align: center;
      padding: 20px;
      color: #666;
      margin-top: 50px;
    }
  </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary fs-4" href="index.php">✈️ BookMyFlight</a>

    <div class="ms-auto d-flex align-items-center">
      <a href="customer_care.php" class="btn btn-outline-info me-2">☎️ Customer Care</a>

      <?php if (isset($_SESSION['username'])): ?>
        <a href="dashboard.php" class="btn btn-outline-success me-2">📋 My Bookings</a>
        <span class="me-3 fw-semibold text-success">
          Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> 👋
        </span>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-primary">👤 Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>


<!-- HERO SECTION (restored) -->
<section class="hero">
  <h1>Fly Smarter, Travel Better 🌏</h1>
  <p>Book your next flight with exclusive offers and comfort beyond the clouds.</p>
  <a href="#offers" class="btn btn-light mt-3">Explore Offers</a>
</section>


<!-- OFFERS SECTION (restored layout) -->
<div id="offers" class="container offers text-center" style="margin-top:60px;">
  <h2 class="text-primary mb-4">💸 Exclusive Offers for You</h2>

  <div class="row g-4">
    <div class="col-md-4">
      <img src="Offer_image.jpg" alt="Offer 1">
      <p class="mt-2 fw-semibold">Get 25% Off on Early Bookings</p>
    </div>

    <div class="col-md-4">
      <img src="seats_image.png" alt="Seats upgrade">
      <p class="mt-2 fw-semibold">Enjoy Business & First-Class Upgrades</p>
    </div>

    <div class="col-md-4">
      <img src="aircraft_image.png" alt="Weekend deals">
      <p class="mt-2 fw-semibold">Special Weekend Deals on Domestic Flights</p>
    </div>
  </div>
</div>


<!-- SEARCH BAR -->
<div class="container text-center search-box">
  <form class="d-flex" method="GET">
    <input class="form-control me-2" type="search" name="search"
           placeholder="Search by Source or Destination..."
           value="<?php echo htmlspecialchars($search); ?>">
    <button class="btn btn-primary" type="submit">Search</button>
  </form>
</div>


<!-- FLIGHTS TABLE -->
<div class="container mt-4">
  <div class="card p-4">
    <h4 class="text-center mb-3 text-primary">Available Flights</h4>

    <table class="table table-bordered table-striped text-center align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Source</th>
          <th>Destination</th>
          <th>Date</th>
          <th>Time</th>
          <th>Model</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
          <td><?php echo $row['F_ID']; ?></td>
          <td><?php echo htmlspecialchars($row['F_Source']); ?></td>
          <td><?php echo htmlspecialchars($row['F_Dest']); ?></td>
          <td><?php echo $row['Date']; ?></td>
          <td><?php echo $row['Time']; ?></td>
          <td><?php echo htmlspecialchars($row['A_Model'] ?? '—'); ?></td>

          <td>
            <?php if (isset($_SESSION['username'])): ?>
              <a href="book.php?fid=<?php echo $row['F_ID']; ?>" class="btn btn-success btn-sm">Book</a>
            <?php else: ?>
              <a href="login.php" class="btn btn-warning btn-sm">Login to Book</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php
          }
        } else {
          echo "<tr><td colspan='7' class='text-muted'>No flights found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

  </div>
</div>


<!-- FOOTER -->
<footer>
  © 2025 BookMyFlight | Designed by Jeevan V R
</footer>

</body>
</html>
