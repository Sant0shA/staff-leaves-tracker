<?php
session_start();
include("../config/db.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}

$user_id = $_SESSION['user_id'];

$staff = $conn->query("
  SELECT id, name 
  FROM staff 
  WHERE created_by = $user_id
  ORDER BY name
");

include("../includes/layout_top.php");
?>

<div class="page-title">My Staff</div>

<?php if(isset($_GET['success'])) { ?>
  <div class="success">Staff added successfully ✅</div>
<?php } ?>

<?php if($staff->num_rows == 0) { ?>

  <div class="card center">
    No staff added yet
  </div>

<?php } ?>

<?php while($row = $staff->fetch_assoc()) { ?>

  <a href="apply_leave.php?staff_id=<?= $row['id'] ?>" class="card">
    <?= $row['name'] ?>
  </a>

<?php } ?>

<a href="add_staff.php" class="fab">＋ Add Staff</a>

<a href="reports.php" class="card">📊 View Reports</a>

<a href="../api/logout.php" class="card">🚪 Logout</a>

<?php include("../includes/layout_bottom.php"); ?>