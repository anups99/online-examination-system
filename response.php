<?php 
session_start();
if(($_SESSION['is_logged_in'])=="")
{
	@header("location:../");
	exit;
}
$from_time1 = date('Y-m-d H:i:s');
$to_time1 = $_SESSION['end_time'];
$timefirst = strtotime($from_time1);
$timesecond = strtotime($to_time1);
$difference_second = $timesecond - $timefirst;
echo gmdate("H:i:s",$difference_second);
 ?>