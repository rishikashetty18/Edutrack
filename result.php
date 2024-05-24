<?php
include "connection.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session

// Simulating user login (this should be done in your login script)
$_SESSION['username'] = 'faculty'; // Example, set this based on actual login
$_SESSION['password'] = 'faculty123'; // Example, set this based on actual login

// Check if the user is faculty
$isFaculty = isset($_SESSION['username']) && $_SESSION['username'] === 'faculty' && isset($_SESSION['password']) && $_SESSION['password'] === 'faculty123';

$classOfStudents=isset($_POST['classOfStudent'])?$_POST['classOfStudent']:'First BCA';//setting default val if unset


$subject=isset($_POST['subject'])?$_POST['classOfStudent']:'C Programming';//setting default val if unset
echo "<h2> $classOfStudents - $subject </h2>";
if(isset($_POST['save']))
{
    $studid=$_POST['studid'];

$exists="SELECT * FROM `result` WHERE student_id='$studid';";  
$res2=$con->query($exists);

$fname=$_POST['firstname'];
$lname=$_POST['lastname'];
$inter1=$_POST['internal1'];+
$inter2=$_POST['internal2'];
$assign=$_POST['assignment'];
$seminar=$_POST['seminar'];
$marks=$_POST['marksobtained'];
$percentage=$_POST['percentage'];
$grade=$_POST['grade'];
$class=$_POST['className'];



if($res2->num_rows <= 0)
$mainquery="INSERT INTO `result`(`student_id`, `first_name`, `last_name`,
 `internal1`, `internal2`, `seminar`, 
`assignment`, `marks_obtained`, `total_marks`, `percentage`,
 `grade`, `class`,`subject`) VALUES ('$studid','$fname','$lname','$inter1',
 '$inter2','$seminar','$assign',
'$marks','40','$percentage','$grade','$class','$subject')";//insert query
else
$mainquery="
UPDATE `result` SET 
`internal1`='$inter1',`internal2`='$inter2',
`seminar`='$seminar',`assignment`='$assign',`marks_obtained`='$marks',
`percentage`='$percentage',`grade`='$grade' WHERE `student_id`='$studid' and `class`='$class' ";//update query

$result=$con->query($mainquery);

if($result)echo "<script>alert('Inserted');</script>";

}


// Define the classes you want to filter (First BCA, Second BCA, Third BCA)
$classesToDisplay = "'$classOfStudents'";

// SQL query to fetch student details for specific classes
 $sql="select * from students where class='$classOfStudents' and subject='$subject'";
$result = $con->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error executing query: ");
}

// Debugging statement to check query result
if ($result->num_rows > 0) {
    echo "Found " . $result->num_rows . " students for the selected classes.<br>";
} else {
    echo "No students found for the selected classes.<br>";
    echo "Executed query: $sql<br>";
}
echo "<table>
<tr>
    <th>Stud_ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Internal 1</th>
    <th>Internal 2</th>
    <th>Assignment</th>
    <th>Seminar</th>
    <th>Marks Obtained</th>
    <th>Total Marks</th>
    <th>Percentage</th>
    <th>Grade</th>
    <th>Save</th>
</tr>";
if ($result->num_rows > 0) {
    // Output data in a styled table format
    echo "<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #1E90FF; /* Dark blue background for headers */
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2; /* Light gray background for even rows */
    }
    .action-column {
        width: 150px; /* Adjust the width as needed */
    }
    input
    {
      width:100px;  
    }
    .submit
    {
        background-color:blue;
        width:44px;
        color:white;
        text-align:center;
        font-size:0.9rem;
        padding:3px;
    }
    </style>";

foreach($result as $row)
{
    $obtainQuery="SELECT * FROM `result` WHERE `student_id`='{$row['student_id']}'and class='$classOfStudents' and subject='$subject' ";
     $inter1=0;
     $inter2=0;
     $assign=0;
     $seminar=0;
     $obtmarks=0;
     $percentage='0';
     $grade='';
    $obtresult=$con->query($obtainQuery);
    foreach($obtresult as $row2){
        $inter1=$row2['internal1'];
        $inter2=$row2['internal2'];
        $assign=$row2['assignment'];
        $seminar=$row2['seminar'];
        $obtmarks=$row2['marks_obtained'];
        $percentage=$row2['percentage'];
        $grade=$row2['grade'];
    }

    echo "<form action='' method='post'><tr>
<td><input type='text' value='{$row['student_id']}' name='studid' readonly></td>
<td><input type='text' value='{$row['first_name']}' name='firstname' readonly></td>
<td><input type='text' value='{$row['last_name']}' name='lastname' readonly required></td>
<td><input type='number' min='0' max='10' onkeyup=calculatemarks({$row['student_id']}) 
   name='internal1' placeholder='Internal 1 marks' value='$inter1' class='{$row['student_id']}'></td>
<td><input type='number' min='0' max='10' onkeyup=calculatemarks({$row['student_id']})  
 name='internal2' placeholder='Internal 2 marks' value='$inter2' class='{$row['student_id']}'></td>
<td><input type='number' min='0' max='10' onkeyup=calculatemarks({$row['student_id']}) 
  name='seminar' placeholder='Seminar marks' value='$seminar' class='{$row['student_id']}'></td>
<td><input type='number' min='0' max='10' onkeyup=calculatemarks({$row['student_id']}) 
  name='assignment' placeholder='Assignment marks' value='$assign' class='{$row['student_id']}'></td>
<td><input type='number' min='0' max='40'  name='marksobtained' placeholder='obtained marks ' 
class='{$row['student_id']}' value='$obtmarks' readonly required></td>
<td><input type='number'  name='totalmarks' placeholder='' value='40'  class='{$row['student_id']}' 
readonly required></td>
<td><input type='text'  name='percentage' placeholder='Percentage' 
class='{$row['student_id']}' value='$percentage%' readonly required></td>
<td><input type='text'  name='grade' placeholder='Grade'
 class='{$row['student_id']}' value='$grade' readonly required></td>
<input type='hidden' value='$classOfStudents' 
name='className' required>
<td><input type='submit' value='Save'  name='save' class='submit'></td>


</tr>
</form>
        ";

}
} else {
    echo "No students found for the selected classes";
}

$con->close();
?>
<script>


    calculatemarks=(cn)=>
    {

        var details=document.getElementsByClassName(cn);
        var obtainedMarks=eval((Number(details[0].value))+Number(details[1].value)+Number(details[2].value)+Number(details[3].value));
        console.log(obtainedMarks);
        details[4].value=obtainedMarks;
        var percentage=(Number(details[4].value)/40)*100;
        details[6].value=percentage+"%";

var grade='' ;
            if (percentage >= 90) {
                grade = 'A+';
            } else if (percentage >= 80) {
                grade = 'A';
            } else if (percentage >= 70) {
                grade = 'B+';
            } else if (percentage >= 60) {
                grade = 'B';
            } else if (percentage >= 50) {
                grade = 'C+';
            } else if (percentage >= 35) {
                grade = 'C';
            } else {
                grade = 'F';
            }
            details[7].value=grade;
        

    }

</script>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
$query="INSERT INTO `result`(`student_id`, `first_name`, `last_name`, `internal1`, `internal2`, 
`seminar`, `assignment`, `marks_obtained`, `total_marks`, `percentage`, `grade`, `class`) VALUES 
('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]',
'[value-9]','[value-10]','[value-11]','[value-12]');";
}
