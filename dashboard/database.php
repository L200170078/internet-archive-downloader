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
		<h1 class="h3 mb-2 text-gray-800">Database</h1>
		<p class="mb-4">Data files that have been successfully collected in cloud storage.</p>

		<div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
            </div>
            <div class="card-body">
              <div class="">
                <table class="table table-bordered display responsive nowrap"   id="dataTable" cellspacing="0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Link</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no=1;
                    $data=mysqli_query($conn, "SELECT * FROM v_tbl_database");
                    while ($row=mysqli_fetch_array($data)) {
                    ?>
                    <tr>
                    	<th><?php echo $no; ?></th>
                    	<th><?php echo $row['name']; ?></th>
                    	<th><?php echo $row['category']; ?></th>
                    	<th><?php echo $row['link']; ?></th>
                    	<th>
                    		<?php
                    		if ($row['status']==1) {
                    		?>
                    		<a href="#" class="btn btn-sm btn-success btn-icon-split">
			                  <span class="icon text-white-50">
			                      <i class="fas fa-check"></i>
			                  </span>
			                  <span class="text">Upload Success</span>
			                </a>
                    		<?php
                    		} else {
                    		?>
                    		<a href="#" class="btn btn-sm btn-danger btn-icon-split">
			                  <span class="icon text-white-50">
			                      <i class="fas fa-times-circle"></i>
			                  </span>
			                  <span class="text">Upload Failed</span>
			                </a>
                    		<?php
                    		}
                    		?>
                    	</th>
                    	<th><?php echo $row['created_at']; ?></th>
                    </tr>
                    <?php
                    	$no++;
                    }
                    ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
		
	</div>
	<!-- Page level plugins -->


    <?php include_once('template/footer.php'); ?>