<?php 
session_start();
include_once 'admin/inc/functions.php';
if(!isset($_GET['act']) || ($_GET['act'])=="")
{
  $_SESSION['error'] = "Requested page not found!!!";
  @header('location:exam.php');
  exit;
}else
{
  $act = sanitize($_GET['act']);
  $id = sanitize($_GET['id']);
  if($act!=substr(md5('recovery'),3,10))
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
							<input type="text" placeholder="Enter the code" name="reset_code" class="form-control" id="reset_code" required />
						</div>
                            
                            <div class="form-group" style="padding-bottom:10px;">
							<input type="password" placeholder="Enter new password" name="password1" class="form-control" id="password" required />
						</div>
                            <div class="form-group" style="padding-bottom:10px;">
							<input type="password" placeholder="Re-enter the new password" name="password2" class="form-control" id="confirm_password" required />
                              <span class="btn-danger hidden" id="password_error"></span>
						</div>
                            
                            
						<div class="form-group">
                          <input type="text" name = "id" class="hidden" value ="<?php echo $id; ?>">
							<input type="submit" name="submission" value="Recover" class="btn btn-primary" id="submit" required />
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
            <script type="text/javascript">
	$('#confirm_password').keyup(function(res){
		var password = $('#password').val();
		var re_password = $('#confirm_password').val();
		if(password == "" || password != re_password){
			$('#password_error').html('Password is either null or does not match.');
			$('#password_error').removeClass('hidden');
			$('#submit').attr('disabled','disabled');
		} else {
			$('#password_error').html('');
			$('#password_error').addClass('hidden');
			$('#submit').removeAttr('disabled','disabled');
		} 
	});
</script>