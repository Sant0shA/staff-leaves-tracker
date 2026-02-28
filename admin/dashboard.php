<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit();
}
?>

<h1>Admin Dashboard</h1>

<a href="../admin/add_staff.php">Add Staff</a><br>
<a href="../admin/add_user.php">Add User</a><br>
<a href="../admin/map_staff.php">Map Staff to User</a>