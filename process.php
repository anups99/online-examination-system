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
if(isset($_POST) && !empty($_POST)){
	//debugger(sanitize($_POST['password']));
	$username = sanitize($_POST['username']);
	//$first_name = sanitize($_POST['first_name']);
	//debugger($username,true);
	$status = array();
	//debugger($user_info,true);
	$user_info = $user->getUserByUsername($username);
	//debugger($user_info,true);
	$password = sha1(sanitize($_POST['password']));
	
	//debugger($user_info,true);
	if(isset($user_info) && $user_info[0]->status == 1){
			if($user_info[0]->password == $password)
			{	
				$_SESSION['role'] = $user_info[0]->role;
				if($_SESSION['role']!=3)
				{
					$_SESSION['error'] = "You must be an admitted student in 4G solutions to take this exam."	;
					@header('location: exam.php');
					exit;
									
				}

				$_SESSION['student_id'] = $user_info[0]->id;
				$_SESSION['first_name'] = $user_info[0]->first_name;
				$_SESSION['full_name'] = $user_info[0]->first_name." ".$user_info[0]->last_name;
				$_SESSION['email_address'] = $user_info[0]->email_address;
				$_SESSION['course_enrolled'] = $user_info[0]->course_enrolled;
				//debugger($lupdate);
				//debugger($_SESSION,true);
				//debugger($lupdate);
				
				

				$_SESSION['is_logged_in'] = 1;
				$_SESSION['success'] = "Hello ".$user_info[0]->first_name."! Welcome to 4G Solutions Examination Dashboard!";

				//debugger($_SESSION,true);
				@header('location: user.php');
				exit;

				
			}else {
				$_SESSION['error'] = "Password does not match.";
				@header('location: exam.php');
				exit;	
			}
	} else {
		$_SESSION['warning'] = "User does not exists or suspended.";
		@header('location: exam.php');
		exit;
	}
} else {
	$_SESSION['warning'] = "Please login first";
	@header('location: exam.php');
	exit;
}