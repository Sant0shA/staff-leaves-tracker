<?php
// user/change_password.php
require_once __DIR__ . '/../includes/db.php';
requireLogin();

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current'] ?? '';
    $new     = $_POST['new']     ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$current || !$new || !$confirm) {
        $error = 'All fields are required.';
    } elseif (strlen($new) < 6) {
        $error = 'New password must be at least 6 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([currentUserId()]);
        $user = $stmt->fetch();

        if (!password_verify($current, $user['password'])) {
            $error = 'Current password is incorrect.';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $upd  = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $upd->execute([$hash, currentUserId()]);
            $success = true;
        }
    }
}

$pageTitle = 'Change Password';
$backUrl   = isAdmin() ? '/admin/dashboard.php' : '/user/dashboard.php';
include __DIR__ . '/../includes/header.php';
?>

<div class="form-wrap">

  <?php if ($success): ?>
    <div class="alert alert-success">✅ Password changed successfully!</div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div style="text-align:center; margin-bottom:28px;">
    <?php $av = avatarColor(currentUserId()); ?>
    <div style="width:64px;height:64px;border-radius:18px;background:<?= $av['bg'] ?>;color:<?= $av['color'] ?>;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:22px;margin:0 auto 12px;">
      <?= initials(currentUserName()) ?>
    </div>
    <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:17px;"><?= htmlspecialchars(currentUserName()) ?></div>
    <div style="font-size:13px;color:var(--text-muted);margin-top:3px;"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></div>
  </div>

  <form method="POST" action="/user/change_password.php">
    <div class="field">
      <label>Current Password</label>
      <input type="password" name="current" placeholder="Your current password" required>
    </div>
    <div class="field">
      <label>New Password</label>
      <input type="password" name="new" placeholder="Min. 6 characters" required>
    </div>
    <div class="field">
      <label>Confirm New Password</label>
      <input type="password" name="confirm" placeholder="Repeat new password" required>
    </div>
    <button type="submit" class="btn-primary">Update Password →</button>
    <a href="<?= $backUrl ?>" class="btn-outline">Cancel</a>
  </form>

  <div style="margin-top:32px;padding-top:24px;border-top:1px solid var(--border);text-align:center;">
    <a href="/logout.php" style="font-size:14px;color:var(--danger);text-decoration:none;font-weight:500;">🚪 Logout</a>
  </div>

</div>

<?php
if (!isAdmin()):
  $activeNav = '';
  include __DIR__ . '/../includes/nav.php';
else: ?>
<nav class="bottom-nav">
  <a href="/admin/dashboard.php" class="nav-item">
    <span class="nav-icon">👥</span>
    <span class="nav-label">Users</span>
  </a>
  <a href="/logout.php" class="nav-item">
    <span class="nav-icon">🚪</span>
    <span class="nav-label">Logout</span>
  </a>
</nav>
</div></body></html>
<?php endif; ?>
