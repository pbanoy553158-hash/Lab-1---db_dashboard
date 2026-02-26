<?php
include "../db.php";
include "../nav.php";
$result = mysqli_query($conn,"SELECT * FROM services ORDER BY service_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic Admin - Services</title>
  <link rel="stylesheet" href="../style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Services</h2>
  <div class="d-flex justify-content-end mb-3">
    <a href="services_add.php" class="btn">+ Add Service</a>
  </div>

  <div class="card-modern table-responsive">
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Name</th><th>Rate</th><th>Active</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row=mysqli_fetch_assoc($result)){ ?>
          <tr>
            <td><?= $row['service_id'] ?></td>
            <td><?= htmlspecialchars($row['service_name']) ?></td>
            <td>₱<?= number_format($row['hourly_rate'],2) ?></td>
            <td><?= $row['is_active']?"Yes":"No" ?></td>
            <td><a href="services_edit.php?id=<?= $row['service_id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>