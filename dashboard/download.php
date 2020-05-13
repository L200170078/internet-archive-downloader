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
	<?php include_once('template/header.php'); ?>

	<div class="container-fluid">
		<h1 class="h3 mb-2 text-gray-800">Download</h1>
		<p class="mb-4">Copy Url from Interner Archieve Collection file's to google drive.</p>

		<div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Form Copyurl</h6>
            </div>
            <div class="card-body">
            	<form method="POST" action="" id="formdownload">
            		<div class="form-group row">
					     <label for="staticEmail" class="col col-form-label">Link</label>
					     <div class="col-sm-11">
					    	<input type="text" name="link" class="form-control" id="link-download" aria-describedby="emailHelp" placeholder="Enter link collection..">
					    	<small id="emailHelp" class="form-text text-muted">Link file from internet arcvieve collection.</small>
					    </div>
					</div>
					<div class="form-group row">
					     <label for="staticEmail" class="col col-form-label">Link</label>
					     <div class="col-sm-11">
					    	<select class="form-control" name="category" id="category-download">
							  <option>Select Category</option>
							  <?php
							  $query=mysqli_query($conn, "SELECT * FROM tbl_category");
							  while ($data=mysqli_fetch_array($query)) {
							  ?>
							  	<option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>
							  <?php
							  }
							  ?>
							</select>
					    	<small id="emailHelp" class="form-text text-muted">Select category file.</small>
					    </div>
					</div>
					<div class="form-group row">
						<label for="staticEmail" class="col col-form-label"></label>
						<div class="col-sm-11">
							<button type="submit" name="submit" class="btn btn-success btn-sm btn-icon-split">
			                    <span class="icon text-white-50">
			                      <i class="fas fa-upload"></i>
			                    </span>
			                    <span class="text">UPLOAD</span>
			                </button>
						</div>
					</div>
            	</form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Result</h6>
            </div>
            <div class="card-body" style="overflow-y:auto; max-height: 300px" id="scroll">
            	<div id="result">
            		<?php
					if (isset($_POST['link']) AND !empty($_POST['category'])) {
						require ("../config/lib/simple-html-dom/simple_html_dom.php");

						$category=mysqli_escape_string($conn, $_POST['category']);
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
									$query=true;
									// $query=mysqli_query($conn, "INSERT INTO `tbl_database`(`id`, `name`, `link`, `status`, `size`,`created_at`, `tbl_category_id`) VALUES ('','$name','$link',1,'$size_cell','$date',1)");
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
								echo str_repeat( ' ', 2048);
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
						echo "<p>The result will shown up in here</p>";
					}
					?>
            		
            	</div>
            	<div id="loading" class="text-center" style="display: none;">
            		<img src="<?php echo $domain; ?>/assets/loading.gif" width="60">
            	</div>
            </div>
        </div>
        <script>
        	$("#scroll").scrollTop($("#scroll")[0].scrollHeight);
        	// $("#formdownload").submit(function(e){
        	// 	e.preventDefault(); 
        	// 	var url = $(this).attr('action');
        	// 	// var link = $('#link-download').val();
        	// 	// var category = $('#category-download').val();

        		
        	// 	$.ajax({
        	// 		url:url,
         //      		data:$(this).serialize(),
         //      		type:'POST',
         //      		beforeSend: function() {
		       //          $("input").attr("disabled",true);
		       //          $("select").attr("disabled",true);
		       //          $("button").attr("disabled",true);
		       //          $("#loading").show();
		       //          $("#result").load("test.php", {
         //   					link: $('#link-download').val(), 
         //   					category: $('#category-download').val()
		      	// 		});
		       //      },
		       //      complete:function() {
		       //          $("input").attr("disabled",false);
		       //          $("select").attr("disabled",false);
		       //          $("button").attr("disabled",false);		
		       //          $("#loading").hide();

		       //      },
		       //      success:function(hasil) {
		       //      }
        	// 	});
        	// });
        </script>
    </div>

    <?php include_once('template/footer.php'); ?>