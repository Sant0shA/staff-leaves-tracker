<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? 'month';

$condition = "MONTH(from_date)=MONTH(CURDATE())
              AND YEAR(from_date)=YEAR(CURDATE())";

if ($filter == "prev") {
  $condition = "MONTH(from_date)=MONTH(CURDATE()-INTERVAL 1 MONTH)
                AND YEAR(from_date)=YEAR(CURDATE()-INTERVAL 1 MONTH)";
}

if ($filter == "year") {
  $condition = "YEAR(from_date)=YEAR(CURDATE())";
}

$query = "
SELECT staff.name, from_date, to_date, notes
FROM leaves
JOIN staff ON staff.id = leaves.staff_id
WHERE leaves.user_id = $user_id
AND $condition
ORDER BY from_date DESC
";

$result = $conn->query($query);

include("../includes/layout_top.php");
?>

<div class="topbar">
  <div class="page-title">Leave Reports</div>
  <a href="dashboard.php">← Back</a>
</div>

<select onchange="location = this.value;" class="input">

  <option value="?filter=month" <?= $filter=='month'?'selected':'' ?>>
    Current Month
  </option>

  <option value="?filter=prev" <?= $filter=='prev'?'selected':'' ?>>
    Previous Month
  </option>

  <option value="?filter=year" <?= $filter=='year'?'selected':'' ?>>
    Current Year
  </option>

</select>

<?php if ($result->num_rows > 0) { ?>
  
  <?php while($row = $result->fetch_assoc()) { ?>

    <div class="card">

      <div class="font-semibold">
        <?= $row['name'] ?>
      </div>

      <div class="text-sm" style="color:#666;">
        <?= $row['from_date'] ?> → <?= $row['to_date'] ?>
      </div>

      <?php if($row['notes']) { ?>
        <div class="text-sm" style="color:#999; margin-top:6px;">
          <?= $row['notes'] ?>
        </div>
      <?php } ?>

    </div>

  <?php } ?>

<?php } else { ?>

  <div class="card">
    No leave records found for this period.
  </div>

<?php } ?>

<?php include("../includes/layout_bottom.php"); ?>