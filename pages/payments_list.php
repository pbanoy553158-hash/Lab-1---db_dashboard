<?php
include "../db.php";
include "../nav.php";

$sql = "
SELECT p.*, b.booking_date, c.full_name
FROM payments p
JOIN bookings b ON p.booking_id = b.booking_id
JOIN clients c ON b.client_id = c.client_id
ORDER BY p.payment_id DESC
";
$result = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic Admin - Payments</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Payments</h2>
  <div class="card-modern table-responsive">
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Client</th><th>Booking ID</th><th>Amount</th><th>Method</th><th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php while($p=mysqli_fetch_assoc($result)){ ?>
          <tr>
            <td><?= $p['payment_id'] ?></td>
            <td><?= htmlspecialchars($p['full_name']) ?></td>
            <td><?= $p['booking_id'] ?></td>
            <td>₱<?= number_format($p['amount_paid'],2) ?></td>
            <td><span class="badge badge-primary"><?= $p['method'] ?></span></td>
            <td><?= $p['payment_date'] ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>