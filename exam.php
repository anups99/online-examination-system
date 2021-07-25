<?php 
session_start();
	include 'inc/header.php';
 include 'inc/top-menu.php'; ?>


	<div id="page-wrapper" style="background-color: lightblue;">

                    <div style="background-color: lightblue; class="col-md-3 col-sm-1 column"></div>
                    <div style="background-color: lightblue; class="col-md-6 col-sm-10 column">
            <div style="padding: 50px;" class="container-fluid">
            	<div class="w3l_banner_nav_right">
            		<?php 
	if(isset($_SESSION['student_id']))
	{
		$_SESSION['warning'] = "You are already logged in.";
		//echo "here";
		include 'admin/inc/notifications.php';
		?>
		<h3 style="text-align: center;"><a href="user.php">Go to dashboard</a></h3>
		<?php 
	exit; 
	}

	
 ?>
                <!-- Page Heading -->
                <div class="row">
                <div class="col-md-10">
                	<?php include 'admin/inc/notifications.php'; ?>
                	<form class="form form-horizontal" method="post" action="<?php echo "process.php"; ?>">
						<div class="form-group">
                          <label>Username:</label>
							<input type="email" name="username" class="form-control" id="username" required />
						</div>
						<div class="form-group">
                          <label>Password:</label>
							<input type="password" name="password" class="form-control" id="password" required />
						</div>
						<div class="form-group">
							<input type="submit" name="submit" class="btn btn-primary" id="submit" required />
						</div>
                	</form>
                </div>
                </div>
                            <?php
                            //print_r($_SERVER);
                            $id = rand(1000,10000);
                            $act = substr(md5('4gnepal'.$id.'4gsolutions'),5,12);
                            ?>
                            <div class ="row">
                              
                              <h4><a style="text-decoration: underline; color:black;" href="<?php echo 'password.php?id='.$id.'&act='.$act; ?>">Forget/Change Password</a></h4>
                              </div>
                              
                               <div class ="row" style="padding-top:20px;">
                              
                              <h4><button style="color:black;" onclick="return alert('Please visit our office or contact us for registering to the online examinations!!!')">Sign up for examination</button></h4>
                              </div>
                <!-- /.row -->
            </div>
            </div>
        </div>
</div>
                    <div style="background-color: lightblue; class="col-md-3 col-sm-1 column"></div>
            <!-- /.container-fluid -->

        </div>
        
<!-- //banner -->
</body>
<?php include 'inc/footer.php';?>