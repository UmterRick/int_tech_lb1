<!DOCTYPE HTML>
<html>
<head>
</head>
<?php
include "connection.php";

$teacher = $_GET['teacher'];
$sqlSelect = $dbh->prepare("SELECT * from $db.teacher inner join $db.lesson_teacher on $db.teacher.ID_teacher = $db.lesson_teacher.FID_teacher inner join $db.lesson on $db.lesson_teacher.FID_Lesson1=$db.lesson.ID_Lesson where $db.teacher.name = :teacher");
$sqlSelect->execute(array('teacher' => $teacher));
echo "<table border ='1'>";
echo "<tr><th>Teacher</th><th>Day</th><th>Number</th><th>Auditorium</th><th>Disciple</th><th>Type</th></tr>";
while ($cell = $sqlSelect->fetch(PDO::FETCH_BOTH)) {
  echo "<tr><td>$cell[1]</td><td>$cell[5]</td><td>$cell[6]</td><td>$cell[7]</td><td>$cell[8]</td><td>$cell[9]</td></tr>";
}
echo "</table>";
?>