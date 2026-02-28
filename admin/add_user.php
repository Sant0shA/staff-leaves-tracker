<?php
include("../config/db.php");

if ($_POST) {

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$conn->query("INSERT INTO users(name,email,password,role)
VALUES('$name','$email','$password','user')");
}
?>

<form method="POST">
<input name="name" placeholder="Name">
<input name="email" placeholder="Email">
<input name="password" placeholder="Password">
<button>Add User</button>
</form>