<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$filter = $_GET['filter'] ?? 'month';

if ($filter == "month") {
  $condition = "MONTH(from_date)=MONTH(CURDATE())
                AND YEAR(from_date)=YEAR(CURDATE())";
}

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
?>
<!DOCTYPE html>
<html>
<head>
<title>Reports</title>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body { font-family: 'Sora', sans-serif; background:#f3f4f6; }
</style>

</head>
<body class="p-4">

<h1 style="color:#363537" class="text-xl font-semibold mb-4">
Leave Reports
</h1>
<select onchange="location = this.value;" class="border p-2 rounded mb-4">

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
<?php while($row = $result->fetch_assoc()) { ?>

<div class="bg-white p-4 rounded-xl shadow mb-3">

  <div class="font-semibold text-lg">
    <?= $row['name'] ?>
  </div>

  <div class="text-sm text-gray-600">
    <?= $row['from_date'] ?> → <?= $row['to_date'] ?>
  </div>

  <?php if($row['notes']) { ?>
    <div class="text-sm text-gray-500 mt-1">
      <?= $row['notes'] ?>
    </div>
  <?php } ?>

</div>

<?php } ?>
</body>
</html>