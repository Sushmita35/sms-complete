<?php
include("database.php");

$id = $_GET['id'];

$query = "
SELECT s.first_name, s.last_name,
r.class, r.subject, r.marks, r.grade, r.status
FROM students s
JOIN results r ON r.student_id = s.student_id
WHERE s.student_id = '$id'
";

$result = mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>
<title>Student Result</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
@media print{
.no-print{
display:none;
}
}
</style>

</head>

<body class="bg-gray-100 p-10">

<div class="max-w-3xl mx-auto bg-white shadow-lg p-8 rounded">

<! HEADER + BACK BUTTON >
<div class="flex justify-between items-center mb-6 no-print">

<h1 class="text-2xl font-bold">
Student Result
</h1>

<a href="manage_results.php"
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
← Back
</a>

</div>

<!-- TITLE -->
<div class="text-center border-b pb-4 mb-6">

<h2 class="text-3xl font-bold">
Student Management System
</h2>

<p class="text-gray-500">
Official Academic Result
</p>

</div>

<!STUDENT DETAILS >
<table class="w-full mb-6">

<tr>
<td class="font-semibold p-2">Student Name:</td>
<td class="p-2">
<?php echo $row['first_name']." ".$row['last_name']; ?>
</td>
</tr>

<tr>
<td class="font-semibold p-2">Class:</td>
<td class="p-2">
<?php echo $row['class']; ?>
</td>
</tr>

<tr>
<td class="font-semibold p-2">Date:</td>
<td class="p-2">
<?php echo date("d M Y"); ?>
</td>
</tr>

</table>

<!- RESULT TABLE >
<table class="w-full border">

<tr class="bg-gray-200">
<th class="p-3 border">Subject</th>
<th class="p-3 border">Marks</th>
<th class="p-3 border">Grade</th>
<th class="p-3 border">Status</th>
</tr>

<tr>
<td class="p-3 border"><?php echo $row['subject']; ?></td>
<td class="p-3 border"><?php echo $row['marks']; ?></td>
<td class="p-3 border font-bold text-blue-600"><?php echo $row['grade']; ?></td>
<td class="p-3 border font-bold <?php echo $row['status']=='PASS'?'text-green-600':'text-red-600'; ?>">
<?php echo $row['status']; ?>
</td>
</tr>

</table>

<!SIGNATURE >
<div class="flex justify-between mt-10">

<div class="text-center">
<hr class="w-40 mb-1">
<p class="text-sm">Administrator</p>
</div>

<div class="text-center">
<hr class="w-40 mb-1">
<p class="text-sm">Official Stamp</p>
</div>

</div>

<! PRINT BUTTON >
<div class="text-center mt-8 no-print">

<button onclick="window.print()" 
class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
Print / Download
</button>

</div>

</div>

</body>

</html>