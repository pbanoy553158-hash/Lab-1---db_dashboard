<?php
include "../db.php";
include "../nav.php";

$id = (int)$_GET['id'];

// Fixed: Prepared statement
$stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

$message = "";

if(isset($_POST['update'])){
  $full_name=$_POST['full_name'];
  $email=$_POST['email'];
  $phone=$_POST['phone'];
  $address=$_POST['address'];

  if($full_name==""||$email==""){
    $message='<div class="alert alert-danger">Name and Email are required!</div>';
  } else {
    $stmt = $conn->prepare("UPDATE clients SET full_name=?, email=?, phone=?, address=? WHERE client_id=?");
    $stmt->bind_param("ssssi",$full_name,$email,$phone,$address,$id);
    $stmt->execute();
    header("Location: clients_list.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic Admin - Edit Client</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Edit Client</h2>
  <?= $message ?>
  <div class="card-modern" style="max-width:600px;">
    <form method="post">
      <label>Full Name*</label>
      <input type="text" name="full_name" value="<?= htmlspecialchars($client['full_name']) ?>" required>

      <label>Email*</label>
      <input type="text" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>

      <label>Phone</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($client['phone']) ?>">

      <label>Address</label>
      <input type="text" name="address" value="<?= htmlspecialchars($client['address']) ?>">

      <button type="submit" name="update" class="btn">Update</button>
    </form>
  </div>
</div>

</body>
</html>