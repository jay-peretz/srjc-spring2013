<?php 
require_once('sql-jperetz.php');
if ($_POST['exercise_id']) {
  $newrow = $mysqli->prepare("insert into homework (student_email, exercise_exercise_id, URL, comment) values (?,?,?,?)");
  $newrow->bind_param('siss',$_POST['student_email'],$_POST['exercise_id'],$_POST['exerciseLink'],$_POST['exerciseComment']);
  $newrow->execute();
  $selectnewhomework = $mysqli->query("select max(homework_id) as homework_id ,  exercise_exercise_id,  student_email , comment, URL from homework group by exercise_exercise_id, student_email , comment, URL order by homework_id desc limit 1");
  $newhomework = $selectnewhomework->fetch_object();
  $selectname = $mysqli->query('select first_name from student where email ="'.$newhomework->student_email.'"');
  $newname = $selectname->fetch_object();
  $newhomework->first_name = $newname->first_name;
  $selectlesson = $mysqli->query("select lesson_id from lesson , exercise where lesson_id = lesson_lesson_id and exercise_id = ".$_POST['exercise_id']);
  $lessonid = $selectlesson->fetch_object();
  $newhomework->lesson_id = $lessonid->lesson_id;
  $mysqli->close();
  require_once('JSON.php');
  $json = new Services_JSON;
  $insertedhomework = $json->encode($newhomework);
  echo $insertedhomework;
} 