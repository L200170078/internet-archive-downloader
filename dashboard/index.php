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

echo "Halaman Dashboard";
?>