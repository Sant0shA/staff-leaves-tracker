<?php
// admin/dashboard.php — Admin master view
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$users = $pdo->query("
    SELECT u.*,
      (SELECT COUNT(*) FROM staff s WHERE s.created_by = u.id) AS staff_count,
      (SELECT COUNT(*) FROM leaves l WHERE l.user_id = u.id)   AS leave_count
    FROM users u
    WHERE u.role = 'user'
    ORDER BY u.name ASC
")->fetchAll();

$totalUsers  = count($users);
$totalStaff  = $pdo->query("SELECT COUNT(*) FROM staff")->fetchColumn();
$totalLeaves = $pdo->query("SELECT COUNT(*) FROM leaves WHERE MONTH(from_date)=MONTH(CURDATE()) AND YEAR(from_date)=YEAR(CURDATE())")->fetchColumn();

$added = isset($_GET['added']);
$pageTitle = 'Admin';
$subTitle  = 'Master Console';
include __DIR__ . '/../includes/header.php';
?>

<div class="scroll-area">

  <?php if ($added): ?>
    <div class="alert alert-success">✅ User created successfully.</div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-num" style="color:var(--accent)"><?= $totalUsers ?></div>
      <div class="stat-label">Users</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:var(--text)"><?= $totalStaff ?></div>
      <div class="stat-label">Total Staff</div>
    </div>
    <div class="stat-card">
      <div class="stat-num" style="color:var(--warning)"><?= $totalLeaves ?></div>
      <div class="stat-label">Leaves (Mo)</div>
    </div>
  </div>

  <!-- Users list -->
  <div class="section-row">
    <div class="section-label" style="margin:0">All Users</div>
    <a href="/admin/add_user.php" class="btn-sm">+ Add User</a>
  </div>

  <div style="margin-top:12px;">
    <?php if (empty($users)): ?>
      <div class="empty">
        <div class="empty-icon">👤</div>
        <div class="empty-text">No users yet</div>
        <div class="empty-sub">Add your first user to get started</div>
      </div>
    <?php else: ?>
      <?php foreach ($users as $u):
        $av = avatarColor($u['id']);
      ?>
        <div class="user-row">
          <div class="staff-avatar" style="background:<?= $av['bg'] ?>;color:<?= $av['color'] ?>">
            <?= initials($u['name']) ?>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-weight:600;font-size:15px;"><?= htmlspecialchars($u['name']) ?></div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($u['email']) ?></div>
            <!-- Reset password link -->
            <a href="/admin/reset_user_password.php?user_id=<?= $u['id'] ?>"
               style="font-size:11px;color:var(--accent);text-decoration:none;font-weight:500;margin-top:4px;display:inline-block;">
              🔑 Reset Password
            </a>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--accent)"><?= $u['staff_count'] ?></div>
            <div style="font-size:11px;color:var(--text-dim)">staff</div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</div>

<!-- Admin bottom nav -->
<nav class="bottom-nav">
  <div class="nav-item active">
    <span class="nav-icon">👥</span>
    <span class="nav-label" style="color:var(--accent)">Users</span>
  </div>
  <a href="/admin/all_leaves.php" class="nav-item">
    <span class="nav-icon">📋</span>
    <span class="nav-label">All Leaves</span>
  </a>
  <a href="/logout.php" class="nav-item">
    <span class="nav-icon">🚪</span>
    <span class="nav-label">Logout</span>
  </a>
</nav>

</div></body></html>