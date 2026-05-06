<?php
include("database.php");

$result = mysqli_query($conn,"SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
<title>Fee Receipts</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

<div class="flex justify-between mb-6">
<h1 class="text-2xl font-bold">Fee Receipts</h1>

<a href="dashboard.php"
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
← Back
</a>
</div>

<table class="w-full">

<tr class="bg-gray-200">
<th class="p-3">Student</th>
<th class="p-3">Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr class="border-t">

<td class="p-3">
<?php echo $row['first_name']." ".$row['last_name']; ?>
</td>

<td class="p-3">

<!  WORKING BUTTON >
<a href="fee_receipt.php?id=<?php echo $row['student_id']; ?>" target="_blank"
class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
Download Receipt
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</body>
</html>