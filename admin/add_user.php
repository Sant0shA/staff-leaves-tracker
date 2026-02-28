<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit();
}

if ($_POST) {

  $name     = $_POST['name'];
  $email    = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $conn->query("
    INSERT INTO users(name,email,password,role)
    VALUES('$name','$email','$password','user')
  ");

  header("Location: dashboard.php?success=user_added");
  exit();
}

include("../includes/layout_top.php");
?>

<div class="topbar">
  <div class="page-title">Add User</div>
  <a href="dashboard.php">← Back</a>
</div>

<form method="POST" class="card">

  <input
    name="name"
    class="input"
    placeholder="Name"
    required
  >

  <input
    name="email"
    type="email"
    class="input"
    placeholder="Email"
    required
  >

  <input
    name="password"
    type="password"
    class="input"
    placeholder="Password"
    required
  >

  <button class="btn btn-primary">
    Save User
  </button>

</form>

<?php include("../includes/layout_bottom.php"); ?>