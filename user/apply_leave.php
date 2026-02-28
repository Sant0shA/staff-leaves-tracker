<?php
session_start();
include("../config/db.php");

$staff_id = $_GET['staff_id'];
$user_id = $_SESSION['user_id'];

if ($_POST) {

$from = $_POST['from'];
$to   = $_POST['to'];
$notes = $_POST['notes'];

$check = $conn->query("
SELECT * FROM leaves
WHERE staff_id=$staff_id
AND (from_date BETWEEN '$from' AND '$to'
OR to_date BETWEEN '$from' AND '$to')
");

if ($check->num_rows > 0) {
  echo "Duplicate leave!";
} else {

$conn->query("
INSERT INTO leaves(staff_id,user_id,from_date,to_date,notes)
VALUES($staff_id,$user_id,'$from','$to','$notes')
");

echo "Leave Applied";
}
}
?>

<form method="POST">
<input type="date" name="from" required>
<input type="date" name="to" required>
<textarea name="notes"></textarea>
<button>Apply Leave</button>
</form>