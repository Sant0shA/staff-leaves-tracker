<?php
// user/dashboard.php — My Staff list
require_once __DIR__ . '/../includes/db.php';
requireLogin();
if (isAdmin()) { header('Location: /admin/dashboard.php'); exit; }

$uid = currentUserId();

// Fetch this user's staff + their active leave status
$stmt = $pdo->prepare("
    SELECT s.*,
      (
        SELECT COUNT(*) FROM leaves l
        WHERE l.staff_id = s.id
          AND CURDATE() BETWEEN l.from_date AND l.to_date
      ) AS on_leave
    FROM staff s
    WHERE s.created_by = ?
    ORDER BY s.name ASC
");
$stmt->execute([$uid]);
$staffList = $stmt->fetchAll();

// Stats
$total    = count($staffList);
$onLeave  = array_sum(array_column($staffList, 'on_leave'));
$present  = $total - $onLeave;

// Current month label
$monthLabel = date('F Y');

$pageTitle = 'Dashboard';
$subTitle  = $monthLabel;
include __DIR__ . '/../includes/header.php';
?>

<div class="scroll-area">

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-num" style="color:var(--text)"><?= $total ?></div>
      <div class="stat-label">Total Staff</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:var(--warning)"><?= $onLeave ?></div>
      <div class="stat-label">On Leave</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:var(--success)"><?= $present ?></div>
      <div class="stat-label">Present</div>
    </div>
  </div>

  <!-- Staff list -->
  <div class="section-label">My Staff</div>

  <?php if (empty($staffList)): ?>
    <div class="empty">
      <div class="empty-icon">👥</div>
      <div class="empty-text">No staff added yet</div>
      <div class="empty-sub">Tap + to add your first staff member</div>
    </div>
  <?php else: ?>
    <?php foreach ($staffList as $s):
      $av = avatarColor($s['id']);
    ?>
      <a href="/user/apply_leave.php?staff_id=<?= $s['id'] ?>" class="staff-card">
        <div class="staff-avatar" style="background:<?= $av['bg'] ?>;color:<?= $av['color'] ?>">
          <?= initials($s['name']) ?>
        </div>
        <div class="staff-info">
          <div class="staff-name"><?= htmlspecialchars($s['name']) ?></div>
          <div class="staff-meta"><?= htmlspecialchars($s['designation'] ?? 'Staff') ?></div>
        </div>
        <span class="badge <?= $s['on_leave'] ? 'badge-leave' : 'badge-present' ?>">
          <?= $s['on_leave'] ? 'On Leave' : 'Present' ?>
        </span>
      </a>
    <?php endforeach; ?>
  <?php endif; ?>

</div><!-- /.scroll-area -->

<!-- FAB: Add Staff -->
<a href="/user/add_staff.php" class="fab" title="Add Staff">+</a>

<?php
$activeNav = 'staff';
include __DIR__ . '/../includes/nav.php';
?>
