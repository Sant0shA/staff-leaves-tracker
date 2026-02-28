<div class="page-title">My Staff</div>

<?php if(isset($_GET['success'])) { ?>
  <div class="success">Staff added successfully ✅</div>
<?php } ?>

<?php while($row = $staff->fetch_assoc()) { ?>

  <a href="apply_leave.php?staff_id=<?= $row['id'] ?>" class="card">
    <?= $row['name'] ?>
  </a>

<?php } ?>

<a href="add_staff.php" class="fab">＋ Add Staff</a>

<a href="reports.php" class="card">📊 View Reports</a>

<a href="../api/logout.php" class="card">🚪 Logout</a>