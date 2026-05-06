<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit(); }

include("database.php");

// SAVE ATTENDANCE
if(isset($_POST['save'])){

    $date = $_POST['date'];

    foreach($_POST['attendance'] as $student_id => $status){

        $student_id = intval($student_id);

        // CHECK IF EXISTS - prepared statement
        $check_stmt = mysqli_prepare($conn, "SELECT * FROM attendance WHERE student_id=? AND date=?");
        mysqli_stmt_bind_param($check_stmt, "is", $student_id, $date);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        mysqli_stmt_close($check_stmt);

        if(mysqli_num_rows($check_result) > 0){

            // UPDATE - prepared statement
            $upd_stmt = mysqli_prepare($conn, "UPDATE attendance SET status=? WHERE student_id=? AND date=?");
            mysqli_stmt_bind_param($upd_stmt, "sis", $status, $student_id, $date);
            mysqli_stmt_execute($upd_stmt);
            mysqli_stmt_close($upd_stmt);

        } else {

            // INSERT - prepared statement
            $ins_stmt = mysqli_prepare($conn, "INSERT INTO attendance (student_id, status, date) VALUES (?,?,?)");
            mysqli_stmt_bind_param($ins_stmt, "iss", $student_id, $status, $date);
            mysqli_stmt_execute($ins_stmt);
            mysqli_stmt_close($ins_stmt);
        }
    }

    echo "<p class='text-green-600 font-bold mb-4'>Attendance Saved Successfully!</p>";
}

// GET STUDENTS
$students = mysqli_query($conn,"SELECT * FROM students");
?>

<!DOCTYPE html>
<html>

<head>
<title>Mark Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">

<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">

<!HEADER WITH BACK BUTTON >
<div class="flex justify-between items-center mb-6">

<h2 class="text-2xl font-bold">Mark Attendance</h2>

<a href="dashboard.php"
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow">
← Back
</a>

</div>

<form method="POST">

<! DATE >
<div class="mb-6">
<label class="block mb-2 font-semibold">Select Date</label>
<input type="date" name="date"
class="border p-2 rounded w-full"
required>
</div>

<table class="w-full">

<tr class="bg-gray-200">
<th class="p-4 text-left">Student</th>
<th class="p-4 text-center">Present</th>
<th class="p-4 text-center">Late</th>
<th class="p-4 text-center">Absent</th>
</tr>

<?php while($row=mysqli_fetch_assoc($students)){ ?>

<tr class="border-t">

<td class="p-4">
<?php echo $row['first_name']." ".$row['last_name']; ?>
</td>

<! PRESENT >
<td class="p-4 text-center">
<input type="radio"
name="attendance[<?php echo $row['student_id']; ?>]"
value="Present"
required>
</td>

<! LATE >
<td class="p-4 text-center">
<input type="radio"
name="attendance[<?php echo $row['student_id']; ?>]"
value="Late">
</td>

<!-- ABSENT -->
<td class="p-4 text-center">
<input type="radio"
name="attendance[<?php echo $row['student_id']; ?>]"
value="Absent">
</td>

</tr>

<?php } ?>

</table>

<!SAVE BUTTON >
<button name="save"
class="mt-6 bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
Save Attendance
</button>

</form>

</div>

</body>

</html>