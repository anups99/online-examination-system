<?php 
session_start();
include_once 'admin/inc/functions.php';
if(!isset($_GET['id']) || ($_GET['id'])=="")
{
  $_SESSION['error'] = "Requested page not found!!!";
  @header('location:exam.php');
  exit;
}else
{
  $act = sanitize($_GET['act']);
  $id = sanitize($_GET['id']);
  if($act!=substr(md5('4gnepal'.$id.'4gsolutions'),5,12))
  {
    $_SESSION['error'] = "Requested action is not valid!!!";
  	@header('location:exam.php');
  	exit;
  }
  
}
	include_once 'inc/header.php';
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
                <h3 style="padding-bottom:10px;" >Password Recovery:</h3>
                	<?php include 'admin/inc/notifications.php'; ?>
                	<form class="form form-horizontal" method="post" action="<?php echo "recover.php"; ?>">
                      <div class="form-group" style="padding-bottom:10px;">
							<input type="text" placeholder="Enter your first name" name="first_name" class="form-control" id="first_name" required />
						</div>
						<div class="form-group" style="padding-bottom:10px;">
							<input type="email" placeholder="Enter Email address related to your account" name="username" class="form-control" id="username" required />
						</div>
                            
						<div class="form-group">
							<input type="submit" name="recovery" value="Recover" class="btn btn-primary" id="submit" required />
						</div>
                	</form>
                </div>
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
</html>