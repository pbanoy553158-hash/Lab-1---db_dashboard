<?php
include "../db.php";
include "../nav.php";

$booking_id=(int)$_GET['booking_id'];

// Fixed: Prepared
$stmt = $conn->prepare("SELECT * FROM bookings WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

// Fixed: Prepared for sum
$stmt = $conn->prepare("SELECT COALESCE(SUM(amount_paid),0) AS paid FROM payments WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$paidRow = $result->fetch_assoc();
$total_paid=$paidRow['paid'];
$balance=$booking['total_cost']-$total_paid;
$message="";

if(isset($_POST['pay'])){
  $amount=(float)$_POST['amount_paid'];
  $method=$_POST['method'];

  if($amount<=0){
    $message='<div class="alert alert-danger">Invalid amount!</div>';
  } else if($amount>$balance){
    $message='<div class="alert alert-danger">Amount exceeds balance!</div>';
  } else {
    $stmt=$conn->prepare("INSERT INTO payments (booking_id, amount_paid, method) VALUES (?,?,?)");
    $stmt->bind_param("ids",$booking_id,$amount,$method);
    $stmt->execute();

    // Re-query paid (prepared)
    $stmt = $conn->prepare("SELECT COALESCE(SUM(amount_paid),0) AS paid FROM payments WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $paidRow2 = $result->fetch_assoc();
    $total_paid2=$paidRow2['paid'];
    $new_balance=$booking['total_cost']-$total_paid2;

    if($new_balance<=0.009){
      // Fixed: Prepared update
      $stmt = $conn->prepare("UPDATE bookings SET status='PAID' WHERE booking_id = ?");
      $stmt->bind_param("i", $booking_id);
      $stmt->execute();
    }

    header("Location: bookings_list.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic Admin - Process Payment</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Process Payment (Booking #<?= $booking_id ?>)</h2>
  <div class="row g-3 mb-4">
    <div class="col-md-4 card-modern p-3 text-center">
      <small>Total Cost</small>
      <h5>₱<?= number_format($booking['total_cost'],2) ?></h5>
    </div>
    <div class="col-md-4 card-modern p-3 text-center">
      <small>Total Paid</small>
      <h5>₱<?= number_format($total_paid,2) ?></h5>
    </div>
    <div class="col-md-4 card-modern p-3 text-center">
      <small>Balance</small>
      <h5>₱<?= number_format($balance,2) ?></h5>
    </div>
  </div>

  <?= $message ?>

  <div class="card-modern" style="max-width:600px;">
    <form method="post">
      <label>Amount Paid</label>
      <input type="number" name="amount_paid" step="0.01" min="0.01" required>

      <label>Method</label>
      <select name="method" required>
        <option value="CASH">CASH</option>
        <option value="GCASH">GCASH</option>
        <option value="CARD">CARD</option>
      </select>

      <button type="submit" name="pay" class="btn">Save Payment</button>
    </form>
  </div>
</div>

</body>
</html>