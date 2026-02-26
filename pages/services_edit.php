<?php
include "../db.php";
include "../nav.php";

$id=(int)$_GET['id'];

// Fixed: Prepared
$stmt = $conn->prepare("SELECT * FROM services WHERE service_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

$message="";

if(isset($_POST['update'])){
  $name=$_POST['service_name'];
  $desc=$_POST['description'];
  $rate=$_POST['hourly_rate'];
  $active=$_POST['is_active'];

  $stmt=$conn->prepare("UPDATE services SET service_name=?,description=?,hourly_rate=?,is_active=? WHERE service_id=?");
  $stmt->bind_param("ssdii",$name,$desc,$rate,$active,$id);
  $stmt->execute();
  header("Location: services_list.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic Admin - Edit Service</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Edit Service</h2>
  <div class="card-modern" style="max-width:600px;">
    <form method="post">
      <label>Service Name</label>
      <input type="text" name="service_name" value="<?= htmlspecialchars($service['service_name']) ?>" required>

      <label>Description</label>
      <textarea name="description" rows="4"><?= htmlspecialchars($service['description']) ?></textarea>

      <label>Hourly Rate</label>
      <input type="number" name="hourly_rate" step="0.01" value="<?= $service['hourly_rate'] ?>" required>

      <label>Active</label>
      <select name="is_active">
        <option value="1" <?= $service['is_active']==1?"selected":"" ?>>Yes</option>
        <option value="0" <?= $service['is_active']==0?"selected":"" ?>>No</option>
      </select>

      <button type="submit" name="update" class="btn">Update</button>
    </form>
  </div>
</div>

</body>
</html>