<?php
include "../db.php";
include "../auth.php";    // or include "auth.php";  depending on folder
include "../nav.php";

$clients = mysqli_query($conn,"SELECT * FROM clients ORDER BY full_name ASC");
$services = mysqli_query($conn,"SELECT * FROM services WHERE is_active=1 ORDER BY service_name ASC");

if(isset($_POST['create'])){
  $client_id=(int)$_POST['client_id'];
  $service_id=(int)$_POST['service_id'];
  $booking_date=$_POST['booking_date'];
  $hours=(float)$_POST['hours'];

  $stmt = $conn->prepare("SELECT hourly_rate FROM services WHERE service_id = ?");
  $stmt->bind_param("i", $service_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $rateRow = $result->fetch_assoc();
  $rate=$rateRow['hourly_rate'];
  $total=$rate*$hours;

  $stmt=$conn->prepare("INSERT INTO bookings (client_id, service_id, booking_date, hours, hourly_rate_snapshot, total_cost, status) VALUES (?,?,?,?,?,?, 'PENDING')");
  $stmt->bind_param("iisddd",$client_id,$service_id,$booking_date,$hours,$rate,$total);
  $stmt->execute();

  header("Location: bookings_list.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic Admin - Create Booking</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Create Booking</h2>
  <div class="card-modern" style="max-width:600px;">
    <form method="post">
      <label>Client</label>
      <select name="client_id" required>
        <?php while($c=mysqli_fetch_assoc($clients)){ ?>
          <option value="<?= $c['client_id'] ?>"><?= htmlspecialchars($c['full_name']) ?></option>
        <?php } ?>
      </select>

      <label>Service</label>
      <select name="service_id" required>
        <?php while($s=mysqli_fetch_assoc($services)){ ?>
          <option value="<?= $s['service_id'] ?>"><?= htmlspecialchars($s['service_name']) ?> (₱<?= number_format($s['hourly_rate'],2) ?>/hr)</option>
        <?php } ?>
      </select>

      <label>Date</label>
      <input type="date" name="booking_date" required>

      <label>Hours</label>
      <input type="number" name="hours" min="1" value="1" required>

      <button type="submit" name="create" class="btn">Create Booking</button>
    </form>
  </div>
</div>

</body>
</html>