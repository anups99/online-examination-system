<?php 
session_start();
if(($_SESSION['is_logged_in'])=="")
{
	$_SESSION['error'] = "Page not found!!!";
	header("location:http://www.4gnepal.com.np/4gsolutions/exam.php");
	exit;
}
session_destroy();
session_start();
header("location:http://www.4gnepal.com.np/4gsolutions/exam.php");
exit;

?>
