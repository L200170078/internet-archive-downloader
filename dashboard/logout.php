<?php
require("../config/connection.php");
require("../config/function.php");
$login = new Login();

$login->logout($conn);
header("Location: ../");
?>