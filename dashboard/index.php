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

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Date</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo date("Y-m-d H:i:s"); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">CPU (Usage)</div>
                      <?php
                      exec('wmic cpu get LoadPercentage', $p);
                      // $cpu = memory_get_usage()/;
                      ?>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $p[1]; ?>%</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-computer fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">RAM (Usage)</div>
                      <?php
                      exec('wmic OS get FreePhysicalMemory', $freememory);
                      exec('wmic memorychip get capacity', $totalMemory);

                      $a = round(array_sum($totalMemory) / 1024 / 1024 / 1024);
                      $b = round($freememory[1] / 1024 / 1024); 
                      ?>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $a-$b; ?> GB of <?php echo $a; ?> GB</div>
                        </div>
                        
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-memory fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">TASK</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                      	<?php
                      	$query=mysqli_query($conn, "SELECT count(id) FROM tbl_database WHERE status =0");
                      	echo mysqli_fetch_array($query)[0];
                      	?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-task fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
          	<div class="col-lg-12 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Welcome</h6>
                </div>
                <div class="card-body">
                  <div class="text-center">
                    <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="<?php echo $domain; ?>/assets/img/undraw_posting_photo.svg" alt="">
                  </div>
                  <p><b>Toolsku</b> is a web based software for downloading to various websites and saved to cloud storage, this usage depends on the hardware and software used and the account of the cloud storage used. cloud storage that can be moved according to what is stated in the third party software is rclone, you must first configure the software. Continue to monitor the use of hardware, software and cloud storage accounts that you use so that the performance of these tools remains good.</p>
                  <a target="_blank" href="">-- Developers</a>
                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

    <?php include_once('template/footer.php'); ?>