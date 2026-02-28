<?php
// index.php — Login page
require_once __DIR__ . '/includes/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: ' . (isAdmin() ? '/admin/dashboard.php' : '/user/dashboard.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['email']   = $user['email'];

            header('Location: ' . ($user['role'] === 'admin' ? '/admin/dashboard.php' : '/user/dashboard.php'));
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="theme-color" content="#F0F4FF">
  <title>Login — TrackLeaves</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/includes/style.css">
</head>
<body>
<div class="app">
  <div class="login-wrap">

    <!-- LOGO -->
    <div class="login-logo-wrap">
      <div class="login-logo-icon">
        <!-- SVG icon: calendar with a checkmark leaf -->
        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
          <!-- Calendar body -->
          <rect x="3" y="6" width="24" height="21" rx="4" fill="white" fill-opacity="0.2"/>
          <rect x="3" y="6" width="24" height="21" rx="4" stroke="white" stroke-width="2"/>
          <!-- Calendar top bar -->
          <rect x="3" y="6" width="24" height="8" rx="4" fill="white" fill-opacity="0.25"/>
          <!-- Calendar pins -->
          <rect x="9" y="3" width="2.5" height="6" rx="1.25" fill="white"/>
          <rect x="18.5" y="3" width="2.5" height="6" rx="1.25" fill="white"/>
          <!-- Checkmark -->
          <path d="M10 18.5L13.5 22L20 14.5" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="login-logo-text">Track<span>Leaves</span></div>
    </div>
    <div class="login-sub">Team leave management, simplified</div>

    <?php if ($error): ?>
      <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/index.php">
      <div class="field">
        <label>Email</label>
        <input
          type="email"
          name="email"
          placeholder="your@email.com"
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
          autocomplete="email"
          required
        >
      </div>

      <div class="field">
        <label>Password</label>
        <input
          type="password"
          name="password"
          placeholder="••••••••"
          autocomplete="current-password"
          required
        >
      </div>

      <button type="submit" class="btn-primary">Sign In →</button>
    </form>

    <div style="text-align:center; margin-top:40px; font-size:12px; color:var(--text-dim);">
      TrackLeaves v1.0 · Contact your admin for access
    </div>

  </div>
</div>
</body>
</html>
