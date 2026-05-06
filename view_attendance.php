<?php
session_start();
include("database.php");

$query = "SELECT * FROM attendance";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
<title>Attendance Records</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<h2 class="text-3xl font-bold mb-6">
Attendance Records
</h2>

<table class="min-w-full bg-white shadow rounded">

<thead class="bg-gray-200">
<tr>
<th class="py-2 px-4">Student ID</th>
<th class="py-2 px-4">Date</th>
<th class="py-2 px-4">Status</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr class="border-t">
<td class="py-2 px-4"><?php echo $row['student_id']; ?></td>
<td class="py-2 px-4"><?php echo $row['date']; ?></td>
<td class="py-2 px-4"><?php echo $row['status']; ?></td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>

</html>