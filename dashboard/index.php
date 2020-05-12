<?php
require("../config/connection.php");
require("../config/function.php");
$login = new Login();

$login_status = $login->cek_login($conn);

if(!$login_status){
	// if cookie & login not authorize
	header("location:../login.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Halaman Dashboard</title>
</head>
<body>
	Selamat datang di dashboard, <a href="logout.php">logout</a>
</body>
</html>