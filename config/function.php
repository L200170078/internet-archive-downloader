<?php
/**
 * 
 */
class Login
{
	
	public function true_login($conn, $user_id, $expired)
	{
		$tgl = date("Y-m-d H:i:s");

		if($expired <> 0){
			// if remind me checked, the expired date is 1 week from now.
			$expireddb = date("Y-m-d H:i:s",strtotime($expired));
		}
		else{
			#if remind me not checked, by default the user can log in for only 6 hours.
			$expireddb = date("Y-m-d H:i:s",strtotime("+6 hours"));
		}

		$ip = mysqli_escape_string($conn, $_SERVER['REMOTE_ADDR']);
		$useragent = mysqli_escape_string($conn,$_SERVER['HTTP_USER_AGENT']);

		// create token with encypt sha1.
		// this $token is important, it will be stored in a cookie later
		$token = sha1($ip.$expireddb."idmanager".microtime());

		// if there is a previous login error with the same IP & user agent before it must be marked first
		// marking is done by changing FLAG from 0 to 9, so that in the next check this data will not be considered
		$update = mysqli_query($conn,"UPDATE tbl_users_log SET stat = 9 WHERE token = '' AND ip = '$ip' AND useragent = '$useragent'");

		// insert data
		$insert = mysqli_query($conn,"INSERT INTO `tbl_users_log`(`id`, `date`, `expired`, `token`, `user_id`, `ip`, `useragent`, `stat`) VALUES ('','$tgl','$expireddb','$token','$user_id','$ip','$useragent',1)");

		if ($insert) {
			$expr = 0;
			if($expired <> 0){
				$expr = intval(strtotime($expired));
			}
			setcookie("idmanager_token", $token, $expr, "/");
			return true;
		} else {
			return false;
		}
	}

	public function cek_login($conn)
	{
		// user condition stated login is:
		// 1. Having $ _COOKIE ['idmanager_token']; (created in the true_login () method)
		// 2. $ _COOKIE ['idmanager_token'] is registered in the tbl_users_log table, and is still not expired
		// 3. IP and User Agent according to the registered token

		if(isset($_COOKIE['idmanager_token'])){
			$token = mysqli_escape_string($conn,$_COOKIE['idmanager_token']);
			$now = mysqli_escape_string($conn,date("Y-m-d H:i:s"));
			$cek = mysqli_query($conn, "SELECT * FROM tbl_users_log WHERE token = '$token' AND expired > '$now'");
			if($cek){
				#if the token exists in the cookie, check the IP and User Agent
				$row = mysqli_fetch_array($cek);
				if($row['ip'] == $_SERVER['REMOTE_ADDR'] || $row['useragent'] == $_SERVER['HTTP_USER_AGENT']){
					$id = $row['user_id'];

					$query = mysqli_query($conn, "SELECT * FROM tbl_users WHERE id='$id'");
					$data = mysqli_fetch_array($query);
					return $data;
				}

			} else {
				return false;
			}
		}

		return false;

	}
}
?>