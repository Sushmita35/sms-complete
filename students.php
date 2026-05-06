<?php
include("database.php");

$query = "SELECT * FROM students";
$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>
<title>Students</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<div class="flex justify-between items-center mb-6">

<h2 class="text-2xl font-bold">
Students
</h2>

<a href="dashboard.php"
class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
← Back to Dashboard
</a>

</div>

<table class="w-full bg-white shadow rounded">

<tr class="bg-blue-200">

<th class="p-3">Student ID</th>
<th class="p-3">First Name</th>
<th class="p-3">Last Name</th>
<th class="p-3">Email</th>

</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr class="border-t">

<td class="p-3"><?php echo $row['student_id']; ?></td>
<td class="p-3"><?php echo $row['first_name']; ?></td>
<td class="p-3"><?php echo $row['last_name']; ?></td>
<td class="p-3"><?php echo $row['email']; ?></td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>

</html>