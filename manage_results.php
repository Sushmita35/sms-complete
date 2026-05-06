<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit(); }

include("database.php");

// INSERT RESULT - prepared statement
if(isset($_POST['submit'])){

    $student_id = intval($_POST['student_id']);
    $class      = $_POST['class'];
    $subject    = $_POST['subject'];
    $marks      = intval($_POST['marks']);

    // GRADE
    if($marks >= 80){ $grade = "A"; }
    elseif($marks >= 70){ $grade = "B"; }
    elseif($marks >= 60){ $grade = "C"; }
    elseif($marks >= 50){ $grade = "D"; }
    else{ $grade = "F"; }

    $status = ($marks >= 40) ? "PASS" : "FAIL";

    $stmt = mysqli_prepare($conn,"
    INSERT INTO results (student_id, class, subject, marks, grade, status)
    VALUES (?,?,?,?,?,?)
    ");
    mysqli_stmt_bind_param($stmt, "ississ", $student_id, $class, $subject, $marks, $grade, $status);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: manage_results.php?msg=saved");
    exit();
}

// GET STUDENTS
$students = mysqli_query($conn,"SELECT * FROM students");

// GET RESULTS
$results = mysqli_query($conn,"
SELECT r.result_id, s.student_id, s.first_name, s.last_name,
r.class, r.subject, r.marks, r.grade, r.status
FROM results r
JOIN students s ON r.student_id = s.student_id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Results</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">

<div class="max-w-5xl mx-auto">

<!-- SUCCESS MESSAGE -->
<?php if(isset($_GET['msg'])){ ?>
<div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
<?php 
if($_GET['msg']=="saved") echo "✅ Result saved!";
if($_GET['msg']=="updated") echo "✅ Result updated!";
?>
</div>
<?php } ?>

<!-- FORM -->
<div class="bg-white p-6 rounded shadow mb-8">

<h2 class="text-2xl font-bold mb-4">Add Result</h2>

<form method="POST">

<div class="mb-4">
<label>Select Student</label>
<select name="student_id" class="w-full border p-2 rounded" required>
<option value="">Select</option>

<?php while($row=mysqli_fetch_assoc($students)){ ?>
<option value="<?php echo $row['student_id']; ?>">
<?php echo htmlspecialchars($row['first_name']." ".$row['last_name']); ?>
</option>
<?php } ?>

</select>
</div>

<div class="mb-4">
<label>Class</label>
<input type="text" name="class" class="w-full border p-2 rounded" required>
</div>

<div class="mb-4">
<label>Subject</label>
<input type="text" name="subject" class="w-full border p-2 rounded" required>
</div>

<div class="mb-4">
<label>Marks</label>
<input type="number" name="marks" class="w-full border p-2 rounded" required>
</div>

<button name="submit"
class="bg-green-600 text-white px-4 py-2 rounded">
Save Result
</button>

</form>

</div>

<!-- RESULTS TABLE -->
<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-4">Saved Results</h2>

<table class="w-full">

<tr class="bg-gray-200">
<th class="p-3">Student</th>
<th class="p-3">Class</th>
<th class="p-3">Subject</th>
<th class="p-3">Marks</th>
<th class="p-3">Grade</th>
<th class="p-3">Status</th>
<th class="p-3">Actions</th>
</tr>

<?php while($row=mysqli_fetch_assoc($results)){ ?>

<tr class="border-t">

<td class="p-3"><?php echo htmlspecialchars($row['first_name']." ".$row['last_name']); ?></td>
<td class="p-3"><?php echo htmlspecialchars($row['class']); ?></td>
<td class="p-3"><?php echo htmlspecialchars($row['subject']); ?></td>
<td class="p-3"><?php echo $row['marks']; ?></td>

<td class="p-3 text-blue-600 font-bold"><?php echo $row['grade']; ?></td>

<td class="p-3 font-bold <?php echo $row['status']=='PASS'?'text-green-600':'text-red-600'; ?>">
<?php echo $row['status']; ?>
</td>

<td class="p-3 space-x-2">

<!-- EDIT BUTTON -->
<a href="edit_result.php?id=<?php echo $row['result_id']; ?>"
class="bg-yellow-500 text-white px-3 py-1 rounded">
Edit
</a>

<!DOWNLOAD >
<a href="download_result.php?id=<?php echo $row['student_id']; ?>"
class="bg-blue-500 text-white px-3 py-1 rounded">
Download
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

<a href="dashboard.php" class="mt-5 inline-block text-blue-500">
← Back to Dashboard
</a>

</div>

</body>
</html>