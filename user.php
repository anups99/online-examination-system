<?php
session_start();
if(!isset($_SESSION['student_id']) || $_SESSION['student_id'] == "" || !isset($_SESSION['role']) ){
    session_destroy();
    @header('location: exam.php');
    exit;
}
include 'inc/header.php';
?>

<div class="page-wrapper" style="background-color: lightblue;">
<?php 
include 'inc/top-menu.php';
include 'admin/class/database.php';
include 'admin/class/course.php';
include 'admin/class/user.php';
include 'admin/class/question.php';
include 'admin/class/result.php';
$question = new Question();
$course = new Course();
$user = new User();
$result = new Result();

?>
<div class="container-fluid">
	<div class="column col-md-3 col-sm-1"></div>
	<div class="column col-md-6 col-sm-12" style="background-color:ghostwhite; padding-top:50px; ">
		<?php include 'admin/inc/notifications.php'; ?>
<h2 style="text-align: center;"><?php echo $_SESSION['first_name']." Dashboard"; ?></h2>


<?php 
	$course_name = $course->getCourseById($_SESSION['course_enrolled']);
    $currentdatetime = strtotime('now');
    $formatteddatetime = date("Y-m-d H:i:s", $currentdatetime);
    $all_question = $question->getQuestionsByCourse($_SESSION['course_enrolled'],$formatteddatetime);
	//debugger($all_question);

	//debugger($course_name,true);

	
    $act = substr(md5($course_name[0]->title.$_SESSION['first_name']),5,13);
	?>
    <div class="col-md-12" style="padding-top: 40px;">
	 	<form  action="<?php echo SITE_URL."/portal.php?act=".$act; ?>" class="form form-horizontal" method="post" onsubmit="return confirm('When you start your exam, make sure you donot press back, submit or refresh button. It will automatically submit your answer and you will not be able to retake the exam.');" enctype="multipart/form-data">
	 					<div class="form-group">
                            <select name="course_name"   required id="course_name" class="form-control">
                                <option style="text-align: center;" value="" disabled selected>--View available examinations--</option>
                                <?php 
                                    if($all_question){
                                        foreach($all_question as $course){
                                            $check_available = $result->checkattemptedexam($course->question_set_name,$_SESSION['student_id']);
                                             //debugger($course->question_set_name);
                                            if($check_available==0)
                                            {


                                ?>
                                <option value="<?php echo $course->question_set_name;?>">
                                        <?php echo $course->question_set_name;?>
                                </option>
                                <?php
                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                        <input type="submit" class="form-control btn btn-success" name="submit" id="submit" value="Take Exam" >
                    </div>
        </form>
    </div>
         <div class="col-md-12" style="padding-top: 40px; color:black;">
          <h3 style="text-align:center;">Previous results:</h3>
        <table class="table table-bordered table-stripped">
                    <thead>
                        <th style="color:darkblue;">S.N.</th>
                        <th style="color:darkblue;">Set name</th>
                        <th style="color:darkblue;">Exam date</th>
                        <th style="color:darkblue;">Score</th>
                        <th style="color:darkblue;">Attempts</th>
                        <th style="color:darkblue;">Duration</th>
                        </thead>
                    <tbody>
                        <?php
                          $results = $result->getResultsByUser($_SESSION['student_id']); 
                            if($results){
                                foreach($results as $key => $value){
                                 
                                ?>
                                    <tr>
                                        <td style="text-align:center; color:inherit;"><?php echo $key+1;?></td>
                                        <td style="text-align:center; color:inherit;"><?php echo $value->set_name;?></td>
                                        <td style="text-align:center; color:inherit;"><?php echo ($value->added_date);?></td>  
                                        <td style="text-align:center; color:inherit;"><?php echo  $value->score;?></td>
                                        <td style="text-align:center; color:inherit;"><?php echo  $value->attempted.'/'.$value->total_questions;?></td>
                                        <td style="text-align:center; color:inherit;"><?php 
                                        if(!strtotime($value->updated_date))
                                        {
                                          echo "<center>incomplete</center>";
                                        }else
                                        {
                                          echo (round((strtotime($value->finished_date)-strtotime($value->added_date))/60,0))."min";
                                        }
                                  
                                  ?></td>
                                    </tr>
                                <?php
                                }
                            }else
                            {?>
                              <tr><td colspan="6" style="text-align:center; color:black;">No flashed results</td></tr>
                           <?php }
                        ?>
                    </tbody>
                </table>
          </div>
   <h3 style="padding-top: 20px;  text-align: center; "><a class="btn btn-success form-control" href="http://4gnepal.com.np">GO to home page</a></h3>
<h3 style="padding-top: 10px; padding-bottom:20px; text-align: center; "><a class="btn btn-success form-control" href="<?php echo SITE_URL."/logout.php/"; ?>">Log out</a></h3>
      </div>
    </div>
<div class="col-md-3 col-sm-1 column"></div>


</div>
</div>
</body>
<?php include 'inc/footer.php';?>
  