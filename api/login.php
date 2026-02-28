<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
  header("Location: ../index.php");
  exit();
}

$email    = $_POST['email'];
$password = $_POST['password'];

$q = $conn->query("SELECT * FROM users WHERE email='$email'");
$user = $q->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {

  $_SESSION['user_id'] = $user['id'];
  $_SESSION['role']    = $user['role'];

  if ($user['role'] == 'admin') {
    header("Location: ../admin/dashboard.php");
  } else {
    header("Location: ../user/dashboard.php");
  }

  exit();

} else {

  header("Location: ../index.php?error=invalid");
  exit();
}