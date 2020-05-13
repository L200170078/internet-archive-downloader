<?php
if (isset($_POST['link']) AND !empty($_POST['category'])) {
	require ("../config/connection.php");
	require ("../config/lib/simple-html-dom/simple_html_dom.php");

	$category=mysqli_escape_string($conn, $_POST['category']);
	$a=1;
	ob_start();
	while ($a <= 100) {
		echo $a."<br>";
		echo str_repeat( ' ', 2048);
		ob_flush();
		flush();
		usleep(25000);
		$a++;
	}
	exit;
	$html = file_get_html($_POST['link']);
	$firstTr = true;

	$size=0;

	$table = $html->find('table.directory-listing-table tbody',0);
	if (ob_get_level() == 0) ob_start();
	foreach ($table->find('tr') as $tr) {
		if(!$firstTr) {
    		// YOUR LOGIC FOR A TR HERE
    		$link_cell = $tr->find('td',0);
    		$link = $link_cell->find('a',0);
			$size_cell = $tr->find('td',2)->plaintext;

			if (strpos($size_cell, 'M')) {
				$size_cell = explode('M', $size_cell);
				$size_cell = $size_cell[0];
			} else {
				$size_cell = explode('K', $size_cell);
				$size_cell = $size_cell[0];
				$size_cell=round($size_cell/1024);
			}
			$size_cell=mysqli_escape_string($conn,$size_cell);
			$size+=$size_cell;
			$name=mysqli_escape_string($conn,$link->plaintext);
			$link=mysqli_escape_string($conn,$_POST['link'].'/'.$link->href);
			$date=mysqli_escape_string($conn,date("Y-m-d H:i:s"));

			// exec("rclone copyurl $link gogole-drive:ROM/Nintendo-64-Internet-Archive -a",$result);

			$result="";

			if ($result=="") {
				$query=mysqli_query($conn, "INSERT INTO `tbl_database`(`id`, `name`, `link`, `status`, `size`,`created_at`, `tbl_category_id`) VALUES ('','$name','$link',1,'$size_cell','$date',1)");
				if ($query) {
					echo '<b>Name</b>:'.$name. '<b> | </b><b>link</b>:'.$link.' <b>|</b> '.' <b>Size</b>:'.$size_cell.'M'.' <b>|</b> <b>Date</b>: '.$date.' <b>|</b> <b style="color:green">OK</b><br>';			
				} else {
					echo '<b>Name</b>:'.$name. '<b> | </b><b>link</b>:'.$link.' <b>|</b> '.' <b>Size</b>:'.$size_cell.'M'.' <b>|</b> <b>Date</b>: '.$date.' <b>|</b> <b style="color:red">FAILED{02}</b><br>';
				}
			} else {
				$query=mysqli_query($conn, "INSERT INTO `tbl_database`(`id`, `name`, `link`, `status`, `size`,`created_at`, `tbl_category_id`) VALUES ('','$name','$link',0,'$size_cell','$date',1)");
				if ($query) {
					echo '<b>Name</b>:'.$name. '<b> | </b><b>link</b>:'.$link.' <b>|</b> '.' <b>Size</b>:'.$size_cell.'M'.' <b>|</b> <b>Date</b>: '.$date.' <b>|</b> <b style="color:red">FAILED{01}</b><br>';
							
				} else {
					echo '<b>Name</b>:'.$name. '<b> | </b><b>link</b>:'.$link.' <b>|</b> '.' <b>Size</b>:'.$size_cell.'M'.' <b>|</b> <b>Date</b>: '.$date.' <b>|</b> <b style="color:red">FAILED{02}</b><br>';
				}

			}
			
			ob_flush();
			flush();
			usleep(25000);
  		} else {
  			$firstTr = false;
  		}

	}

	ob_end_flush();

	echo "Done, Total size ".$size."M";

} else {
	echo "Please, fill form correctly.";
}
?>