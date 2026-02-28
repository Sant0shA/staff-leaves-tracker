<?php
// user/add_staff.php — Add new staff member
require_once __DIR__ . '/../includes/db.php';
requireLogin();
if (isAdmin()) { header('Location: /admin/dashboard.php'); exit; }

$uid   = currentUserId();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');

    if (!$name) {
        $error = 'Staff name is required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO staff (name, designation, created_by) VALUES (?, ?, ?)");
        $stmt->execute([$name, $designation, $uid]);
        header('Location: /user/dashboard.php?added=1');
        exit;
    }
}

$pageTitle = 'Add Staff';
$backUrl   = '/user/dashboard.php';
include __DIR__ . '/../includes/header.php';
?>

<div class="form-wrap">

  <?php if ($error): ?>
    <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="/user/add_staff.php">

    <div class="field">
      <label>Full Name *</label>
      <input
        type="text"
        name="name"
        placeholder="e.g. Priya Sharma"
        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
        autocomplete="off"
        required
      >
    </div>

    <div class="field">
      <label>Role / Designation</label>
      <input
        type="text"
        name="designation"
        placeholder="e.g. Sales Executive"
        value="<?= htmlspecialchars($_POST['designation'] ?? '') ?>"
        autocomplete="off"
      >
    </div>

    <button type="submit" class="btn-primary">Add Staff Member →</button>
    <a href="/user/dashboard.php" class="btn-outline">Cancel</a>

  </form>
</div>

<?php
$activeNav = 'staff';
include __DIR__ . '/../includes/nav.php';
?>
