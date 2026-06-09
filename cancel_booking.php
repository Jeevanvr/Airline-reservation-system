<?php
include("db.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['rid'])) {
    $rid = intval($_GET['rid']);
    $uid = $_SESSION['uid'];

    // Verify that the booking belongs to the logged-in user
    $check = mysqli_query($conn, "SELECT * FROM Reservation WHERE R_ID='$rid' AND U_ID='$uid'");
    if ($check && mysqli_num_rows($check) == 1) {
        // Update status to Cancelled
        mysqli_query($conn, "UPDATE Reservation SET R_Status='Cancelled' WHERE R_ID='$rid'");

        // Optional: If you track seat counts, you can increment here.
        // Example (uncomment and adjust column names if you have F_Available):
        // mysqli_query($conn, "UPDATE Flight SET F_Available = F_Available + 1 WHERE F_ID = (SELECT F_ID FROM Reservation WHERE R_ID='$rid')");

        $_SESSION['msg'] = "✅ Your booking has been cancelled successfully.";
    } else {
        $_SESSION['msg'] = "⚠️ You are not authorized to cancel this booking.";
    }
}

header("Location: dashboard.php");
exit;
?>
