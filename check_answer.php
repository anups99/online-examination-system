<?php 

session_start();
include 'inc/config.php';
include 'admin/inc/functions.php';
include "admin/class/database.php";
include 'admin/class/user.php';
include 'admin/class/course.php';
include 'admin/class/result.php';
include 'admin/class/question.php';

	if($_SESSION['is_logged_in'] =="")
{
	$_SESSION['error'] = "Your log in session has been lost.";
	header("location:exam.php");
	exit;
}
if(isset($_POST) && $_POST['course_id']!="")
{

//debugger($_POST,true);

//include_once 'admin/inc/config.php';



$question = new Question();
$result = new Result();
$attempts = 0;
$correct = 0;
$total = 0;

foreach ($_POST['asked_question_id'] as $key => $value) 
{
	$total++;
	//debugger($key);
	if(isset($_POST[$value]))
	{
		$attempts++;
		//debugger($key);
	//	debugger($_POST,true);
		$right_answer = $question->getRightAnswerById($value);
		//debugger($right_answer);
		if(($right_answer[0]->right_answer)==$_POST[$value])
		{
			$correct++;
		}
		//debugger($value);

	}
	
}
$incorrect = $attempts - $correct;
//debugger($total);
//debugger($attempts);
//debugger($correct);
//debugger($incorrect,true);
$data =array();
$data['set_name'] = sanitize($_POST['question_name']);
$data['student_id'] = $_SESSION['student_id'];
$data['total_questions'] = $total;
$start = sanitize($_POST['result_id']);
if($correct>0)
{
$data['correct'] = $correct;
}

if($attempts>0)
{
$data['attempted'] = $attempts;


$score = $correct-($incorrect*0.1);
$data['score'] = $score;
$data['finished_date'] = date('Y-m-d H:i:s');
}
$success = $result->updateResult($data,$start);
//debugger($_SESSION);
//debugger($data,true);
if($success)
{
session_destroy();
session_start();
$_SESSION['success'] = "Thank you for taking the exam. You attempted: <strong>".$attempts."</strong> questions. Your result will be published once the deadline of examination is reached.";
@header("location:exam.php");
exit;
}
else
{
	$_SESSION['error'] = "Sorry! There was problem in submitting your answer sheet. Please retake your exam";
	@header("location:user.php");
	exit;
}
}else{
	$_SESSION['error'] = "Sorry! This was illegal action";
	@header("location:exam.php");
	exit;
}
?>