<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>TrackLeaves Login</title>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container center">

<div class="card">

<div class="page-title">TrackLeaves</div>

<?php if(isset($_GET['error'])) { ?>
  <div class="error">Invalid email or password ❌</div>
<?php } ?>

<form method="POST" action="api/login.php">

<input name="email" placeholder="Email" class="input" required>

<input name="password" type="password" placeholder="Password" class="input" required>

<button class="btn btn-primary">Login</button>

</form>

</div>

</div>

</body>
</html>