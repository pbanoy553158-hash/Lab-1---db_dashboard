<?php
include "../db.php";
include "../auth.php";    // or include "auth.php";  depending on folder
include "../nav.php";

$message = "";

if (isset($_POST['assign'])) {
    $booking_id = (int)$_POST['booking_id'];
    $tool_id    = (int)$_POST['tool_id'];
    $qty        = max(1, (int)$_POST['qty_used']);

    $stmt = $conn->prepare("SELECT quantity_available FROM tools WHERE tool_id = ?");
    $stmt->bind_param("i", $tool_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $toolRow = $result->fetch_assoc();

    if (!$toolRow || $qty > $toolRow['quantity_available']) {
        $message = '<div class="alert alert-danger">Not enough available tools!</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO booking_tools (booking_id, tool_id, qty_used) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $booking_id, $tool_id, $qty);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE tools SET quantity_available = quantity_available - ? WHERE tool_id = ?");
        $stmt->bind_param("ii", $qty, $tool_id);
        $stmt->execute();

        $message = '<div class="alert alert-success">Tool assigned successfully!</div>';
    }
}

$tools_result    = mysqli_query($conn, "SELECT * FROM tools ORDER BY tool_name ASC");
$bookings_result = mysqli_query($conn, "SELECT booking_id FROM bookings ORDER BY booking_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tools / Inventory – Dental Clinic Admin</title>
  <link rel="stylesheet" href="../style.css?v=<?= time() ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
  <h1 class="page-title">Tools / Inventory</h1>
  <p class="page-subtitle">Manage tools and assign them to bookings</p>

  <?php if ($message): ?>
    <?= $message ?>
  <?php endif; ?>

  <div class="card-row">
    <?php while ($tool = mysqli_fetch_assoc($tools_result)): ?>
      <div class="card">
        <div class="card-icon">
          <?php
            $n = strtolower($tool['tool_name'] ?? '');
            if (stripos($n, 'hammer')) echo '🔨';
            else if (stripos($n, 'ladder')) echo '🪜';
            else if (stripos($n, 'drill') || stripos($n, 'power')) echo '🔧';
            else if (stripos($n, 'saw')) echo '🪚';
            else echo '🛠️';
          ?>
        </div>
        <div class="card-label"><?= htmlspecialchars($tool['tool_name'] ?? '—') ?></div>
        <div class="card-value"><?= $tool['quantity_available'] ?? '?' ?></div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="form-card">
    <h2 style="text-align:center; margin-bottom:2rem;">Assign Tool to Booking</h2>

    <form method="post">
      <div class="form-group">
        <label>Booking ID</label>
        <select name="booking_id" required>
          <?php mysqli_data_seek($bookings_result, 0); ?>
          <?php while ($b = mysqli_fetch_assoc($bookings_result)): ?>
            <option value="<?= $b['booking_id'] ?>">#<?= $b['booking_id'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Tool</label>
        <select name="tool_id" required>
          <?php mysqli_data_seek($tools_result, 0); ?>
          <?php while ($t = mysqli_fetch_assoc($tools_result)): ?>
            <option value="<?= $t['tool_id'] ?>">
              <?= htmlspecialchars($t['tool_name']) ?> (<?= $t['quantity_available'] ?? '?' ?> avail)
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Quantity Used</label>
        <input type="number" name="qty_used" min="1" value="1" required>
      </div>

      <button type="submit" name="assign" class="btn-pill btn-pink">Assign Tool</button>
    </form>
  </div>
</div>

</body>
</html>