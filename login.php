<?php
if (isset($_POST['submit'])) {
	include_once("config/connection.php");

	$username = mysqli_escape_string($conn, $_POST['username']);
	$password = mysqli_escape_string($conn, $_POST['password']);

	$query = mysqli_query($conn, "SELECT * FROM tbl_users WHERE username='$username'");
	$row = mysqli_num_rows($query);

	if ($row > 0) {
		// users found
		$data = mysqli_fetch_array($query);
		if (password_verify($password, $data['password'])) {
			// password match
			include_once('config/function.php');

			if (isset($_POST['remember'])) {
				if ($_POST['remember'] == 1) {
					$expired = '+1 week';
				} else {
					$expired = 0;
				}
			} else {
				$expired = 0;	
			}

			$login = new Login();
			$result = $login->true_login($conn, $data['id'], $expired);
			if ($result) {
				header('Location: ./dashboard');
			} else {
				header("Location: ./?error=3");
			}
		} else {
			header("Location: ./?error=2");
		}

	}  else {
		header("Location: ./?error=1");
	}
} else {
	header("Location: ./");
}
?>