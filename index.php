<!DOCTYPE HTML>
<html>
<head>
</head>
<body>
    <h3>Усиченко Владислав. КИУКИ-19-5, Вариант 1</h3>
<form method="get" action="1.php">
    <p> Вывести расписание занятий группы
        <select name="groups" id="groups">
            <option>Группа</option>
    </p>
    <?php
        include "connection.php";
        $sqlSelect = "SELECT DISTINCT * FROM $db.groups";
        foreach ($dbh->query($sqlSelect) as $cell) {
            echo "<option>";
            print($cell[1]);
            echo "</option>";
        }
    ?>
    </select>
   <button>Поиск</button>
</form>

<form method="get" action="2.php">
    <p>Вывести расписание преподавателя 
        <select name="teacher" id="teacher">
            <option>Преподаватели</option>
    </p>
    <?php
        $sqlSelect = "SELECT DISTINCT * FROM $db.teacher";
        foreach ($dbh->query($sqlSelect) as $cell) {
            echo "<option>";
            print($cell[1]);
            echo "</option>";
        } 
    ?>
    </select>
    <button>Поиск</button>
</form>

<form method="get" action="3.php">
    <p>Вывести расписание для аудитории
        <select name="auditorium" id="auditorium">
            <option>Аудитория</option>
    </p>
    <?php
    $sqlSelect = "SELECT DISTINCT auditorium FROM $db.lesson";
        foreach ($dbh->query($sqlSelect) as $cell) {
            echo "<option>";
            print($cell[0]);
            echo "</option>";
        }
    ?>
    </select>
    <button>Поиск</button>
</form>

<p><b>Добавление нового ПЗ</b></p>
<form method="get" action="" id="form">
    <p>Введите день недели</p>
    <input required name="week_day" value="Monday">
    <p>Введите номер пары</p>
    <input required name="lesson_number" type="number" value="1" min="1" max="6" step="1">
    <p>Введите номер аудитории</p>
    <input required name="auditorium">
    <p>Введите название дисциплины</p>
    <input required name="disciple">
    <p><b> Выберите преподавателя<select name="name">      
    <?php
        $sqlSelect = "SELECT * FROM $db.teacher";
        echo "<option>Преподаватель</option>";
            
        foreach($dbh->query($sqlSelect) as $cell)
        {   echo "<option>";
            print($cell[1]);
            echo "</option>";
        }
        
        echo "</select>" 
    ?>
    Выберите группу
    <select name ="title" >
    <?php $sqlSelect = "SELECT * FROM $db.groups";
        echo "<option>Группа</option>";   
        foreach($dbh->query($sqlSelect) as $cell)
        {   echo "<option>";
            print($cell[1]);
            echo "</option>";
        }
        echo "</select></b></p>" 
    ?>
    <input type="submit" value="Добавить">
</form>

<?php
if( isset($_GET['week_day']) && isset($_GET['lesson_number']) && isset($_GET['auditorium']) && isset($_GET['disciple']) && isset($_GET['name']) && isset($_GET['title'])){

$week_day = $_GET['week_day'];
$lesson_number=$_GET['lesson_number'];
$auditorium=$_GET['auditorium'];
$disciple=$_GET['disciple'];
$type = 'Practical';
$name=$_GET['name'];
$title=$_GET['title'];
try {
    $dbh->exec("set names utf8");
    $alter = "ALTER TABLE $db.lesson CHANGE lesson.ID_Lesson lesson.ID_Lesson INT(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 1";
    $st= $dbh->prepare($alter);
    $st->execute();
    $sql = "INSERT INTO $db.lesson (week_day, lesson_number, auditorium, disciple, type) values ( ?, ?, ?, ?, ?)";
    $stmt= $dbh->prepare($sql);
    $stmt->execute([$week_day, $lesson_number, $auditorium, $disciple, $type]);
    $sql = $dbh->prepare("SELECT * from $db.teacher where $db.teacher.name = :name");
    $sql->execute(array('name' => $name));
    $sql=$sql->fetch();
    $teacher_id = $sql[0];
    $sql = $dbh->prepare("SELECT max(ID_Lesson) from $db.lesson");
    $sql->execute(array());
    $sql=$sql->fetch();
    $lesson_id = $sql[0];
    $sql = "INSERT INTO $db.lesson_teacher (FID_Teacher, FID_Lesson1) values ( ?, ?)";
    $st = $dbh->prepare($sql);
    $st->execute([$teacher_id, $lesson_id]);
    $sql = $dbh->prepare("SELECT * from $db.groups where $db.groups.title = :title");
    $sql->execute(array('title' => $title));
    $sql=$sql->fetch(PDO::FETCH_BOTH);
    $group_id = $sql[0];
    $sql = $dbh->prepare("SELECT max(ID_Lesson) from $db.lesson");
    $sql->execute(array());
    $sql=$sql->fetch(PDO::FETCH_BOTH);
    $lesson_id = $sql[0];
    $sql = "INSERT INTO $db.lesson_groups (FID_Lesson2, FID_Groups) values ( ?, ?)";
    $st = $dbh->prepare($sql);
    $st->execute([$lesson_id, $group_id]);   
} catch (PDOException $e) {
    print "Ошибка!: " . $e->getMessage() . "<br/>";
}
}
?>

</body>

</html>