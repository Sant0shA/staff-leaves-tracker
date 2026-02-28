<?php
$host = "localhost";
$user = "u248088683_admin";
$pass = "o!JM#*zt3U4iQN";
$db   = "u248088683_staff_leaves";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("DB Connection failed");
}
?>