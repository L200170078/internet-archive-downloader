<?php
$domain	= "http://localhost/internet-archive-downloader/";
$server = "localhost";
$user = "root";
$password = "";
$database = "idmanager";

$conn = mysqli_connect($server,$user,$password) or die ("Database connection failed.");

mysqli_select_db($conn, $database) or die ("Database does not exist.");
?>