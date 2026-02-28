<?php
// includes/header.php
// Usage: include at top of every page AFTER setting $pageTitle and $backUrl (optional)
$pageTitle = $pageTitle ?? 'TrackLeaves';
$backUrl   = $backUrl   ?? null;
$subTitle  = $subTitle  ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="theme-color" content="#0D0F14">
  <title><?= htmlspecialchars($pageTitle) ?> — TrackLeaves</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/includes/style.css">
</head>
<body>
<div class="app">

  <div class="header">
    <div class="header-left">
      <?php if ($backUrl): ?>
        <a href="<?= htmlspecialchars($backUrl) ?>" class="back-btn">←</a>
      <?php endif; ?>
      <div>
        <div class="logo">Track<span>Leaves</span></div>
        <?php if ($subTitle): ?>
          <div class="header-sub"><?= htmlspecialchars($subTitle) ?></div>
        <?php endif; ?>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
      <?php if (isAdmin()): ?>
        <span class="badge badge-admin">⚡ Admin</span>
      <?php endif; ?>
      <a href="/logout.php" class="avatar <?= isAdmin() ? 'admin' : '' ?>" title="Logout">
        <?= initials(currentUserName()) ?>
      </a>
    </div>
  </div>
