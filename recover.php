<?php
session_start();
include 'inc/config.php';
include 'admin/inc/functions.php';
include "admin/class/database.php";
include 'admin/class/user.php';
include 'admin/class/course.php';
$user = new User();
$course = new Course();
//debugger($_POST,true);
if(isset($_POST['recovery']) && !empty($_POST['recovery']))
{
  //debugger($_POST,true);
	//debugger(sanitize($_POST['password']),true);
	$username = sanitize($_POST['username']);
  	$first_name = strtolower(sanitize($_POST['first_name']));
 	//debugger($username);
  	//debugger($first_name);
  	$info = $user->getUserByUsername($username);
  	if(strtolower($info[0]->first_name) == $first_name)
    {
      if($info[0]->role==3)
      {
        $code = substr(md5('change-password'.rand(2,2000).$info[0]->id),5,4);
        $data = array();
        $data['reset_code'] = $code;
        $from_time1 = date('Y-m-d H:i:s');
		$to_time1 = $info[0]->updated_date ;
		$timefirst = strtotime($from_time1);
		$timesecond = strtotime($to_time1);
		$difference_second = $timefirst-$timesecond ;
		//debugger($difference_second,true);
        //debugger($code);
        if($difference_second<=1800)
        {
          $_SESSION['error'] = "Sorry! You cannot receive any recovery code now. Make sure you didnot requested it for last 30 minutes.";
          @header("location:exam.php");
          exit;
        }
        $to = $username;
        $subject = "Password Update Request";
        $mailContent = "Your code is ".$code;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: 4gsloutions<info@4gnepal.com.np>' . "\r\n";
        $sent = mail($to,$subject,$mailContent,$headers);
        if($sent)
        {
          $update = $user->updateUser($data,$info[0]->id);
          if($update)
          {
            $null= substr(md5('recovery'),3,10);
          $_SESSION['success'] = "Please enter the code sent to your mail. The code may be even in spam box of your mail.";
            
          @header("location:recoverform.php?id=".$info[0]->id."&act=".$null);
          exit;
          }else
          {
            $_SESSION['error'] = "Sorry , there was problem in the recovery process. Please try again!!!";
  		@header("location:exam.php");
  		exit;
          }
          
        }else
        {
        $_SESSION['error'] = "Sorry , there was problem in sending the code. Make sure the email you have registered is correct!!!";
  		@header("location:exam.php");
  		exit;
        }
        //debugger($code,true);
      }else
      {
        $_SESSION['error'] = "You donot have permission to change the info of this user!!!";
  		@header("location:exam.php");
  		exit;
      }
      
    }else
    {
    $_SESSION['error'] = "The requested user is not found. Please re-check the provided informations!!!";
  	@header("location:exam.php");
  	exit;
    }
}else if(isset($_POST['submission']) && $_POST['submission']!="")
{
 // debugger($_POST,true);
  $id = sanitize($_POST['id']);
  $code = sanitize($_POST['reset_code']);
  $password = sha1(sanitize($_POST['password1']));
  $info = $user->getUserInfoById('*',$id);
//  debugger($info,true);
  if($info)
  {
    if($code == $info[0]->reset_code)
    {
      $data = array();
      $data['password'] = $password;
      $success = $user->updateUser($data,$id);
      if($success)
      {
        $_SESSION['success'] = "Password changed successfully!";
        @header("location:exam.php");
        exit;
      }else
      {
        $_SESSION['error'] = "Sorry , there was problem in changing the password!!!";
  		@header("location:exam.php");
  		exit;        
      }
    }else
    {
      $_SESSION['error'] = "The verification code did not match !!!";
  		@header("location:exam.php");
  		exit;
    }
  }else
    {
      $_SESSION['error'] = "The user is already deleted or suspended !!!";
  		@header("location:exam.php");
  		exit;
    }

}
else
{
  $_SESSION['error'] = "Invalid access!!!";
  @header("location:exam.php");
  exit;
}
?>