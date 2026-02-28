<?php
// reset_password.php
// Upload this to public_html/trackleaves/
// Visit: trackleaves.atrios.in/reset_password.php
// DELETE THIS FILE after you're done!

$passwords = [
    'admin123' => password_hash('admin123', PASSWORD_DEFAULT),
    'user123'  => password_hash('user123',  PASSWORD_DEFAULT),
];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Password Reset Helper</title>
  <style>
    body { font-family: monospace; padding: 30px; background: #f0f4ff; }
    h2 { margin-bottom: 20px; }
    .box { background: #fff; border: 1px solid #dde3f0; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
    label { font-size: 12px; color: #64748b; display: block; margin-bottom: 6px; }
    code { display: block; background: #f8faff; padding: 10px; border-radius: 6px; word-break: break-all; font-size: 13px; }
    .sql { background: #1e293b; color: #7dd3fc; padding: 16px; border-radius: 10px; font-size: 13px; line-height: 1.7; margin-top: 20px; }
    .warn { background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 13px; color: #92400e; }
  </style>
</head>
<body>

<h2>🔑 TrackLeaves — Password Reset Helper</h2>

<?php foreach ($passwords as $plain => $hash): ?>
<div class="box">
  <label>Plain password: <strong><?= $plain ?></strong></label>
  <code><?= $hash ?></code>
</div>
<?php endforeach; ?>

<div class="sql">
  <strong style="color:#fff">Run this SQL in phpMyAdmin to fix passwords:</strong><br><br>

  UPDATE `users` SET `password` = '<?= $passwords['admin123'] ?>'
  WHERE `email` = 'admin@trackleaves.com';<br><br>

  UPDATE `users` SET `password` = '<?= $passwords['user123'] ?>'
  WHERE `email` = 'vikram@trackleaves.com';
</div>

<div class="warn">
  ⚠️ <strong>Delete this file immediately after use!</strong><br>
  This file should never be left on a live server.
</div>

</body>
</html>
