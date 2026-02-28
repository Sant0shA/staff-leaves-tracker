<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}

if (!isset($_GET['staff_id'])) {
  header("Location: dashboard.php");
  exit();
}

$staff_id = $_GET['staff_id'];
$user_id  = $_SESSION['user_id'];

$message = "";

if ($_POST) {

  $from  = $_POST['from'];
  $to    = $_POST['to'];
  $notes = $_POST['notes'];

  $check = $conn->query("
    SELECT id FROM leaves
    WHERE staff_id = $staff_id
    AND (
      from_date BETWEEN '$from' AND '$to'
      OR to_date BETWEEN '$from' AND '$to'
    )
  ");

  if ($check->num_rows > 0) {
    $message = "Duplicate leave for selected dates ❌";
  } else {

    $conn->query("
      INSERT INTO leaves(staff_id,user_id,from_date,to_date,notes)
      VALUES($staff_id,$user_id,'$from','$to','$notes')
    ");

    $message = "Leave applied successfully ✅";
  }
}

include("../includes/layout_top.php");
?>

<div class="topbar">
  <div class="page-title">Apply Leave</div>
  <a href="staff.php">← Back</a>
</div>

<?php if($message) { ?>
  <div class="card"><?= $message ?></div>
<?php } ?>

<form method="POST" class="card">

<label>From date</label>
<input type="date" name="from" class="input" required>

<label>To date</label>
<input type="date" name="to" class="input" required>

<textarea name="notes" class="input" placeholder="Notes (optional)"></textarea>

<button class="btn btn-primary">Apply Leave</button>

</form>

<?php include("../includes/layout_bottom.php"); ?>