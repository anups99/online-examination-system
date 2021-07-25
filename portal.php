<?php
session_start();
include_once 'admin/inc/functions.php';
if($_SESSION['is_logged_in'] == "")
{
	$_SESSION['error'] = "Please log in first";
	@header('location:exam.php');
	exit;
}
if(!isset($_POST) || ($_POST==" ") || $_POST['course_name']=="")
{	
	$_SESSION['error'] = "Sorry! There was problem in starting this exam. Please try again later!";
	@header("location:user.php");
	exit;
}
include_once 'inc/header.php';
?>
<?php
//require $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
include_once 'admin/class/database.php';
include 'admin/class/course.php';
include 'admin/class/user.php';
include 'admin/class/question.php';
include 'admin/class/result.php';
$user = new User();
$course = new Course();
$question = new Question();
$result = new Result();
//debugger($_POST);
//if(!$_POST)



$question_name = sanitize($_POST['course_name']);
$_SESSION['question_name'] = $question_name;
$temp = $question->getQuestionsByTitle($question_name);
$current_course = $course->getCourseById($temp[0]->course_id);
//debugger($current_course);
$additional_course = $question->getCourseSubName($question_name);
//debugger($additional_course);
if($additional_course[0]->default_set)
{
    $all_questions = $question->getQuestionsByTitlewithSubSet($question_name,$additional_course[0]->default_set);
    //debugger($all_questions,true);
    $length1 = $question->getExamLength($question_name);
    $length2 = $question->getExamLength($additional_course[0]->default_set);
    $updated_length = $length1[0]->exam_length + $length2[0]->exam_length;
    $qn1 = $question->getQuesnNo($question_name);
    $qn2 = $question->getQuesnNo($additional_course[0]->default_set);
    $updated_no_of_questions = $qn1[0]->number_of_questions + $qn2[0]->number_of_questions;
  //  debugger($updated_length);
//    debugger($updated_no_of_questions);
    $all_questions[0]->exam_length = $updated_length;
    $all_questions[0]->number_of_questions = $updated_no_of_questions;
   // debugger($all_questions,true);
}
else
{
    $all_questions = $question->getQuestionsForTitle($question_name);
 // debugger($all_questions,true);
}
//exit;
$_SESSION['exam_length'] = $all_questions[0]->exam_length;
$_SESSION['start_time'] = date('Y-m-d H:i:s');
$end_time = date('Y-m-d H:i:s',strtotime('+'.$_SESSION['exam_length'].'minutes',strtotime($_SESSION['start_time'])));
$_SESSION['end_time'] = $end_time;
//debugger($all_questions,true);
$check_available = $result->checkattemptedexam($question_name,$_SESSION['student_id']);
//debugger($check_available,true);
//exit;
if($check_available==1)
{
    session_destroy();
    session_start();
    $_SESSION['success'] = "You have completed your exam.";
    @header("location:exam.php");
    exit;
}
else
{
// debugger($all_questions,true);
$data =array();
$data['set_name'] = $question_name;
$data['student_id'] = $_SESSION['student_id'];
$data['total_questions'] = $all_questions[0]->number_of_questions;
//debugger($data);
$start = $result->addResult($data);
//debugger($start,true);
}


//debugger($end_time);
//debugger($current_course);
//debugger($all_questions);
//debugger($_SESSION,true);
?>	
<div id="page-wrapper">
    <div class="container-fluid">
    	<div class="row">
        	<div class="col-md-9 col-sm-9 col-lg-9" ></div>
        	 <div  class="col-md-3 col-lg-3 col-sm-3" id="response" style="font-size: 25px;  position: fixed; right: -7px;"></div>
             <div class ="col-md-3 col-lg-3 col-md-3" id="remaining" style = "font-size:20px; position: fixed; right:-7px; top:20px;"></div>
        </div>
        <!-- Page Heading -->
        <div class="row">
        	<div class="col-lg-3 col-md-3 col-sm-1"></div>
            <div class="col-lg-6 col-md-6 col-sm-8" style="border-style: solid;">
            	<div class="col-md-9 col-lg-9 col-sm-9">
            <?php include INC_PATH.'inc/notifications.php';?>
                <h4 class="page-header" style="text-align:center; margin-top: 10px;">
                    <?php echo $current_course[0]->title." Model Question";?>
                    <?php echo "<br/>". $question_name; ?><br>
                    <b>4G Engineering Solutions Pvt Ltd.</b><br>
                    <b>Kupondole, Lalitpur, Nepal</b>
                </h4>
                </div> 
               <div class="col-lg-3 col-md-3 col-sm-3">
               	<br>
            	<strong>F.M. <?php echo $all_questions[0]->number_of_questions; ?></strong><br>
            	<strong>P.M. <?php echo ($all_questions[0]->number_of_questions)*0.4; ?></strong>
            </div>          
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1"></div>         
            <div class="col-lg-10 col-md-10 col-sm-10">
            	<div class="col-lg-12 col-md-12 col-sm-12">
                    <h4 style=" font-size: 25px;  text-decoration: italic; padding-top: 50px;">Student Name: <?php echo $_SESSION['full_name']; ?></h4>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
            		<h4 style=" font-size: 25px; font-style: bold; text-decoration: underline; padding-top: 50px;">Select the best Alternatives</h4>
            	</div>
                <form action="<?php echo SITE_URL.'/check_answer.php';?>" id="form_id" onsubmit="return confirm('Are you sure you want to submit this answer sheet?? You cannot retake this exam again?');" class="form form-horizontal" method="post" enctype="multipart/form-data">

                    <?php
                    $options =array();

                    $i=0;
                    foreach ($all_questions as $key => $value)
                    {
                        $options = array($value->right_answer,$value->wrong_answer1,$value->wrong_answer2,$value->wrong_answer3);
                        shuffle($options);
                        //debugger($options);
                        //debugger($value,true);

              			$i++;
                     ?>
                    <div class="form-group">
                        <div class="col-md-12" style="padding-top: 5px;">
                        <p  style="font-size: 20px; text-align: justify; padding-top: 15px;"><?php echo $i.". ".htmlspecialchars_decode($value->question); ?></p>
                        <br>
                        </div>

                        <div class="row" >
                            <div class="col-md-6 col-sm-12" style="padding-top: 5px;">
                                <input type="radio"  value="<?php echo $options[0]; ?>" name="<?php echo $value->id; ?>"   /><strong style=" font-weight: 500;">&nbsp;&nbsp;<?php echo $options[0]; ?></strong>
                            </div>

                            <div class="col-md-6 col-sm-12" style="padding-top: 5px;">
                                <input type="radio" value="<?php echo $options[1]; ?>" name="<?php echo $value->id; ?>"  /><strong style=" font-weight: 500;">&nbsp;&nbsp;<?php echo $options[1]; ?></strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12" style="padding-top: 5px;">
                                <input type="radio" value="<?php echo $options[2]; ?>" name="<?php echo $value->id; ?>"/><strong style=" font-weight: 500;">&nbsp;&nbsp;<?php echo $options[2]; ?></strong>
                            </div>

                            <div class="col-md-6 col-sm-12" style="padding-top: 5px;">
                                <input type="radio" value="<?php echo $options[3]; ?>" name="<?php echo $value->id; ?>"  /><strong style=" font-weight: 500;">&nbsp;&nbsp;<?php echo $options[3]; ?></strong>
                            </div>
                            <div class="hidden">
                                <input type="text" name="asked_question_id[]" value="<?php echo $value->id; ?>">
                            </div>
                           

                        </div>
                    </div>
                    <?php     
                    } ?>
                    <div class="form-group">
                        <input type="text" name="course_id" class="hidden" value ="<?php echo $all_questions[0]->course_id; ?>">
                        <input type="text" name="result_id" class="hidden" value ="<?php echo $start; ?>">
                        <input type="text" name="question_name" class="hidden" value ="<?php echo $question_name; ?>">
                        <input type="submit" name="question_add" id="question_add" value="Submit Answer Sheet"  class="btn btn-success">
                    </div>
                </form>
            </div>
                        <div class="col-lg-1 col-md-1 col-sm-1"></div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<?php include 'inc/footer.php'; ?> 

<script type="text/javascript">
	
	

    setInterval(function()
		{
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","response.php",false);
			xmlhttp.send(null);
			document.getElementById('response').innerHTML=xmlhttp.responseText;
		},1000);


exam_length =  '<?php echo (($all_questions[0]->exam_length)*60000); ?>';
var auto_refresh = setInterval(function() { submitform(); },exam_length);

// Form submit function.
function submitform()
{
alert('Time up!!! Answer sheet is submitting.....');
document.getElementById("form_id").submit();
}


function disable_f5(e)
{
  if ((e.which || e.keyCode) == 116)
  {
      e.preventDefault();
  }
}

$(document).ready(function(){
    $(document).bind("keydown", disable_f5);    
});


  </script>
