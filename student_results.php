<?php
include("database.php");

$result = mysqli_query($conn,"SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Results</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">

<div class="flex justify-between mb-6">
<h1 class="text-3xl font-bold">Student Results</h1>

<a href="dashboard.php"
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
← Back
</a>
</div>

<table class="w-full bg-white shadow rounded">

<tr class="bg-gray-200">
<th class="p-3">Student Name</th>
<th class="p-3">Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr class="border-t">

<td class="p-3">
<?php echo $row['first_name']." ".$row['last_name']; ?>
</td>

<td class="p-3">

<a href="download_result.php?id=<?php echo $row['student_id']; ?>"
class="bg-green-500 text-white px-3 py-1 rounded">
Download Result
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>