<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}

if ($_POST) {

  $name    = $_POST['name'];
  $user_id = $_SESSION['user_id'];

  $conn->query("
    INSERT INTO staff(name, created_by)
    VALUES('$name', '$user_id')
  ");

  header("Location: dashboard.php?success=staff_added");
  exit();
}

include("../includes/layout_top.php");
?>

<div class="topbar">
  <div class="page-title">Add Staff</div>
  <a href="dashboard.php">← Back</a>
</div>

<form method="POST" class="card">

  <input
    name="name"
    class="input"
    placeholder="Staff name"
    required
  >

  <button class="btn btn-primary">
    Save Staff
  </button>

</form>

<?php include("../includes/layout_bottom.php"); ?>