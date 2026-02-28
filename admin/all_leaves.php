<?php
// admin/all_leaves.php — Admin view of all leaves across all users
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$filter  = $_GET['filter'] ?? 'this_month';
$allowed = ['this_month', 'last_month', 'this_year'];
if (!in_array($filter, $allowed)) $filter = 'this_month';

switch ($filter) {
    case 'this_month':
        $dateFrom = date('Y-m-01');
        $dateTo   = date('Y-m-t');
        $label    = date('F Y');
        break;
    case 'last_month':
        $dateFrom = date('Y-m-01', strtotime('first day of last month'));
        $dateTo   = date('Y-m-t',  strtotime('last day of last month'));
        $label    = date('F Y', strtotime('last month'));
        break;
    case 'this_year':
        $dateFrom = date('Y-01-01');
        $dateTo   = date('Y-12-31');
        $label    = date('Y');
        break;
}

$stmt = $pdo->prepare("
    SELECT l.*, s.name AS staff_name, s.designation,
           u.name AS user_name
    FROM leaves l
    JOIN staff s ON l.staff_id = s.id
    JOIN users  u ON l.user_id  = u.id
    WHERE l.from_date <= ?
      AND l.to_date   >= ?
    ORDER BY l.from_date DESC
");
$stmt->execute([$dateTo, $dateFrom]);
$leaveList = $stmt->fetchAll();

$totalEntries = count($leaveList);
$totalDays    = 0;
foreach ($leaveList as $l) {
    $d = (new DateTime($l['from_date']))->diff(new DateTime($l['to_date']));
    $totalDays += $d->days + 1;
}

$pageTitle = 'All Leaves';
$subTitle  = $label;
include __DIR__ . '/../includes/header.php';
?>

<div class="scroll-area">

  <!-- Filter chips -->
  <div class="filter-chips">
    <a href="?filter=this_month" class="chip <?= $filter === 'this_month' ? 'active' : '' ?>">This Month</a>
    <a href="?filter=last_month" class="chip <?= $filter === 'last_month' ? 'active' : '' ?>">Last Month</a>
    <a href="?filter=this_year"  class="chip <?= $filter === 'this_year'  ? 'active' : '' ?>">This Year</a>
  </div>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-num" style="color:var(--accent)"><?= $totalEntries ?></div>
      <div class="stat-label">Entries</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:var(--warning)"><?= $totalDays ?></div>
      <div class="stat-label">Total Days</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:var(--success)"><?= count(array_unique(array_column($leaveList, 'staff_id'))) ?></div>
      <div class="stat-label">Staff</div>
    </div>
  </div>

  <div class="section-label">All Leave Entries</div>

  <?php if (empty($leaveList)): ?>
    <div class="empty">
      <div class="empty-icon">📋</div>
      <div class="empty-text">No leave entries found</div>
      <div class="empty-sub">Try a different time period</div>
    </div>
  <?php else: ?>
    <?php foreach ($leaveList as $l):
      $dr = formatDateRange($l['from_date'], $l['to_date']);
    ?>
      <div class="leave-row">
        <div class="leave-dot"></div>
        <div style="flex:1">
          <div class="leave-name"><?= htmlspecialchars($l['staff_name']) ?></div>
          <div class="leave-dates"><?= $dr['label'] ?></div>
          <div class="leave-notes">via <?= htmlspecialchars($l['user_name']) ?><?= $l['notes'] ? ' · ' . htmlspecialchars($l['notes']) : '' ?></div>
        </div>
        <div class="leave-days"><?= $dr['days'] ?>d</div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</div>

<!-- Admin nav -->
<nav class="bottom-nav">
  <a href="/admin/dashboard.php" class="nav-item">
    <span class="nav-icon">👥</span>
    <span class="nav-label">Users</span>
  </a>
  <a href="/admin/all_leaves.php" class="nav-item active">
    <span class="nav-icon">📋</span>
    <span class="nav-label" style="color:var(--accent)">All Leaves</span>
  </a>
  <a href="/logout.php" class="nav-item">
    <span class="nav-icon">🚪</span>
    <span class="nav-label">Logout</span>
  </a>
</nav>

</div>
</body>
</html>
