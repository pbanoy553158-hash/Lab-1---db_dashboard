<?php
include "../db.php";
include "../nav.php";

$message = "";
if(isset($_POST['save'])){
  $full_name = $_POST['full_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  if($full_name=="" || $email==""){
    $message='<div class="alert alert-danger">Name and Email are required!</div>';
  } else {
    $stmt = $conn->prepare("INSERT INTO clients (full_name,email,phone,address) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$full_name,$email,$phone,$address);
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
  <title>Dental Clinic Admin - Add Client</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Add Client</h2>
  <?= $message ?>
  <div class="card-modern" style="max-width:600px;">
    <form method="post">
      <label>Full Name*</label>
      <input type="text" name="full_name" required>

      <label>Email*</label>
      <input type="text" name="email" required>

      <label>Phone</label>
      <input type="text" name="phone">

      <label>Address</label>
      <input type="text" name="address">

      <button type="submit" name="save" class="btn">Save</button>
    </form>
  </div>
</div>

</body>
</html>