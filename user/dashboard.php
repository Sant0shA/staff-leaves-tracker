<?php
session_start();
include("../config/db.php");

$user_id = $_SESSION['user_id'];

$staff = $conn->query("
SELECT staff.id, staff.name
FROM staff
JOIN user_staff_map 
ON staff.id = user_staff_map.staff_id
WHERE user_staff_map.user_id = $user_id
");
?>

<h2>My Staff</h2>

<?php while($row = $staff->fetch_assoc()) { ?>

<div>
<a href="apply_leave.php?staff_id=<?=$row['id']?>">
<?= $row['name'] ?>
</a>
</div>

<?php } ?>