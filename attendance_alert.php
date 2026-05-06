<?php
include("database.php");

$query = "

SELECT 
s.student_id,
s.first_name,
s.last_name,

COUNT(CASE WHEN a.status='Present' THEN 1 END) AS present_days,
COUNT(CASE WHEN a.status='Late'    THEN 1 END) AS late_days,
COUNT(CASE WHEN a.status='Absent'  THEN 1 END) AS absent_days,
COUNT(a.status) AS total_days,

IF(COUNT(a.status)=0,0,
ROUND(
(COUNT(CASE WHEN a.status='Present' THEN 1 END)
+
(COUNT(CASE WHEN a.status='Late' THEN 1 END) * 0.5))
*100 / COUNT(a.status),2)
) AS attendance_percentage

FROM students s

LEFT JOIN attendance a 
ON a.student_id = s.student_id

GROUP BY s.student_id

";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance Analysis</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">

<h2 class="text-2xl font-bold">
Attendance Analysis
</h2>

<a href="dashboard.php"
class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
← Back to Dashboard
</a>

</div>

<table class="w-full bg-white shadow rounded">

<tr class="bg-gray-200">

<th class="p-3">Student</th>
<th class="p-3">Present Days</th>
<th class="p-3">Late Days</th>
<th class="p-3">Absent Days</th>
<th class="p-3">Total Days</th>
<th class="p-3">Attendance %</th>
<th class="p-3">Status</th>

</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr class="border-t">

<td class="p-3">
<?php echo $row['first_name']." ".$row['last_name']; ?>
</td>

<td class="p-3 text-green-700">
<?php echo $row['present_days']; ?>
</td>

<td class="p-3 text-yellow-600">
<?php echo $row['late_days']; ?>
</td>

<td class="p-3 text-red-500">
<?php echo $row['absent_days']; ?>
</td>

<td class="p-3">
<?php echo $row['total_days']; ?>
</td>

<td class="p-3 font-bold">
<?php echo $row['attendance_percentage']; ?>%
</td>

<td class="p-3 font-bold">

<?php
if($row['attendance_percentage'] < 75){
    echo "<span class='text-red-600'>LOW</span>";
}else{
    echo "<span class='text-green-600'>OK</span>";
}
?>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>

</html>