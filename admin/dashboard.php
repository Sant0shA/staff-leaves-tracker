<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit();
}

include("../includes/layout_top.php");
?>

<div class="page-title">Admin Dashboard</div>

<?php if(isset($_GET['success'])) { ?>
  <div class="card" style="color:green;">
    Action completed successfully ✅
  </div>
<?php } ?>

<a href="add_user.php" class="card">
  👤 Add User
</a>

<a href="staff.php" class="card">
  👥 View All Staff
</a>

<!-- Optional if admin can also create staff -->
<a href="add_staff.php" class="card">
  ➕ Add Staff
</a>

<a href="../api/logout.php" class="card" style="color:#EF2D56;">
  🚪 Logout
</a>

<?php include("../includes/layout_bottom.php"); ?>