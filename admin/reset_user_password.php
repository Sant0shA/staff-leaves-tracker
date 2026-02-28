<?php
// admin/reset_user_password.php
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$userId  = (int)($_GET['user_id'] ?? 0);
$error   = '';
$success = false;

// Fetch the user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'user' LIMIT 1");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: /admin/dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new     = $_POST['new']     ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$new || !$confirm) {
        $error = 'Both fields are required.';
    } elseif (strlen($new) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($new !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $upd  = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $upd->execute([$hash, $userId]);
        $success = true;
    }
}

$pageTitle = 'Reset Password';
$backUrl   = '/admin/dashboard.php';
$subTitle  = $user['name'];
include __DIR__ . '/../includes/header.php';
?>

<div class="form-wrap">

  <?php if ($success): ?>
    <div class="alert alert-success">✅ Password reset successfully for <?= htmlspecialchars($user['name']) ?>.</div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- User info -->
  <div style="display:flex;align-items:center;gap:12px;background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:14px 16px;margin-bottom:28px;box-shadow:0 1px 4px rgba(0,0,0,0.05);">
    <?php $av = avatarColor($user['id']); ?>
    <div class="staff-avatar" style="background:<?= $av['bg'] ?>;color:<?= $av['color'] ?>;">
      <?= initials($user['name']) ?>
    </div>
    <div>
      <div style="font-weight:600;font-size:15px;"><?= htmlspecialchars($user['name']) ?></div>
      <div style="font-size:12px;color:var(--text-muted);margin-top:2px;"><?= htmlspecialchars($user['email']) ?></div>
    </div>
  </div>

  <form method="POST" action="/admin/reset_user_password.php?user_id=<?= $userId ?>">
    <div class="field">
      <label>New Password</label>
      <input type="password" name="new" placeholder="Min. 6 characters" required>
    </div>
    <div class="field">
      <label>Confirm New Password</label>
      <input type="password" name="confirm" placeholder="Repeat new password" required>
    </div>
    <button type="submit" class="btn-primary">Reset Password →</button>
    <a href="/admin/dashboard.php" class="btn-outline">Cancel</a>
  </form>

</div>

<!-- Admin nav -->
<nav class="bottom-nav">
  <a href="/admin/dashboard.php" class="nav-item active">
    <span class="nav-icon">👥</span>
    <span class="nav-label" style="color:var(--accent)">Users</span>
  </a>
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
