<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit();
}

$staff = $conn->query("
  SELECT staff.name, users.name AS owner
  FROM staff
  JOIN users ON users.id = staff.created_by
  ORDER BY staff.name
");

include("../includes/layout_top.php");
?>

<div class="topbar">
  <div class="page-title">All Staff</div>
  <a href="dashboard.php">← Back</a>
</div>

<?php if ($staff->num_rows > 0) { ?>

  <?php while($row = $staff->fetch_assoc()) { ?>

    <div class="card">
      <div class="font-semibold"><?= $row['name'] ?></div>
      <div class="text-sm" style="color:#777;">
        Added by: <?= $row['owner'] ?>
      </div>
    </div>

  <?php } ?>

<?php } else { ?>

  <div class="card">
    No staff added yet.
  </div>

<?php } ?>

<?php include("../includes/layout_bottom.php"); ?>