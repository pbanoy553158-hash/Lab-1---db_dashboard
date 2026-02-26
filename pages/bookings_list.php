<?php
include "../db.php";
include "../nav.php";

$sql = "
SELECT b.*, c.full_name AS client_name, s.service_name
FROM bookings b
JOIN clients c ON b.client_id = c.client_id
JOIN services s ON b.service_id = s.service_id
ORDER BY b.booking_id DESC
";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic Admin - Bookings</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Bookings</h2>
  <div class="d-flex justify-content-end mb-3">
    <a href="bookings_create.php" class="btn">+ Create Booking</a>
  </div>

  <div class="card-modern table-responsive">
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Client</th><th>Service</th><th>Date</th><th>Hours</th><th>Total</th><th>Status</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($b=mysqli_fetch_assoc($result)){ ?>
          <tr>
            <td><?= $b['booking_id'] ?></td>
            <td><?= htmlspecialchars($b['client_name']) ?></td>
            <td><?= htmlspecialchars($b['service_name']) ?></td>
            <td><?= $b['booking_date'] ?></td>
            <td><?= $b['hours'] ?></td>
            <td>₱<?= number_format($b['total_cost'],2) ?></td>
            <td>
              <span class="badge <?= $b['status']=='PENDING'?'badge-warning':'badge-success' ?>"><?= $b['status'] ?></span>
            </td>
            <td>
              <a href="payment_process.php?booking_id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-outline-success">Process Payment</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>