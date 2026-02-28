<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;500;600&display=swap" rel="stylesheet">
<style>body{font-family:'Sora',sans-serif}</style>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

<form method="POST" action="api/login.php" class="bg-white p-6 rounded-2xl shadow w-80">
<h2 class="text-xl mb-4">TrackLeaves</h2>

<input name="email" placeholder="Email" class="border p-2 w-full mb-3">
<input name="password" type="password" placeholder="Password" class="border p-2 w-full mb-3">

<button class="bg-[#0CCE6B] w-full p-2 rounded-xl text-white">
Login
</button>

</form>
</body>
</html>