<?php
// admin/add_user.php — Create a new user account
require_once __DIR__ . '/../includes/db.php';
requireAdmin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check email unique
        $chk = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $error = 'That email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins  = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $ins->execute([$name, $email, $hash]);
            header('Location: /admin/dashboard.php?added=1');
            exit;
        }
    }
}

$pageTitle = 'Add User';
$backUrl   = '/admin/dashboard.php';
include __DIR__ . '/../includes/header.php';
?>

<div class="form-wrap">

  <?php if ($error): ?>
    <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="/admin/add_user.php">

    <div class="field">
      <label>Full Name *</label>
      <input
        type="text"
        name="name"
        placeholder="e.g. Meena Joshi"
        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
        required
      >
    </div>

    <div class="field">
      <label>Email *</label>
      <input
        type="email"
        name="email"
        placeholder="meena@company.in"
        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
        required
      >
    </div>

    <div class="field">
      <label>Password *</label>
      <input
        type="password"
        name="password"
        placeholder="Min. 6 characters"
        required
      >
    </div>

    <button type="submit" class="btn-primary">Create Account →</button>
    <a href="/admin/dashboard.php" class="btn-outline">Cancel</a>

  </form>
</div>

<!-- Admin nav simplified -->
<nav class="bottom-nav">
  <a href="/admin/dashboard.php" class="nav-item active">
    <span class="nav-icon">👥</span>
    <span class="nav-label" style="color:var(--accent)">Users</span>
  </a>
  <a href="/logout.php" class="nav-item">
    <span class="nav-icon">🚪</span>
    <span class="nav-label">Logout</span>
  </a>
</nav>

</div>
</body>
</html>
