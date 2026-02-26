<?php
include "db.php";
include "nav.php";

$clients  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM clients"))['c'] ?? 0;
$services = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM services"))['c'] ?? 0;
$bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM bookings"))['c'] ?? 0;
$revRow   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(amount_paid),0) AS s FROM payments"));
$revenue  = $revRow['s'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard – Dental Clinic Admin</title>
  <link rel="stylesheet" href="style.css?v=<?= time() ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h1 class="page-title">Dashboard</h1>
  <p class="page-subtitle">Quick overview of clinic activity</p>

  <div class="card-row">
    <div class="card">
      <div class="card-icon">👥</div>
      <div class="card-label">Total Clients</div>
      <div class="card-value"><?= number_format($clients) ?></div>
    </div>

    <div class="card">
      <div class="card-icon">🦷</div>
      <div class="card-label">Total Services</div>
      <div class="card-value"><?= number_format($services) ?></div>
    </div>

    <div class="card">
      <div class="card-icon">📅</div>
      <div class="card-label">Total Bookings</div>
      <div class="card-value"><?= number_format($bookings) ?></div>
    </div>

    <div class="card">
      <div class="card-icon">₱</div>
      <div class="card-label">Total Revenue</div>
      <div class="card-value">₱<?= number_format($revenue, 2) ?></div>
    </div>
  </div>

  <div class="action-row">
    <a href="pages/clients_add.php" class="btn-pill btn-blue">+ Add New Client</a>
    <a href="pages/bookings_create.php" class="btn-pill btn-blue">+ Create Booking</a>
  </div>
</div>

</body>
</html>