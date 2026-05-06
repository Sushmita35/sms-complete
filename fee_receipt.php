<?php
include("database.php");

if(!isset($_GET['id'])){
    die("No student selected");
}

$id = intval($_GET['id']);

$query = "
SELECT 
students.first_name,
students.last_name,
students.email,
students.phone,
IFNULL(SUM(fees.amount),0) as paid
FROM students
LEFT JOIN fees ON students.student_id = fees.student_id
WHERE students.student_id = '$id'
";

$result = mysqli_query($conn,$query);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

if(!$row){
    die("Student not found");
}

$total_fee = 10000;
$paid = $row['paid'];
$pending = $total_fee - $paid;
?>

<!DOCTYPE html>
<html>
<head>
<title>Fee Receipt</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>

<style>
@media print{
.no-print{ display:none; }
body{ background:white; }
}
</style>

</head>

<body class="bg-gray-200 p-10">

<div class="max-w-4xl mx-auto bg-white p-8 shadow-lg rounded">

<!-- HEADER -->
<div class="flex justify-between items-center border-b pb-4 mb-6">

<div>
<h2 class="text-xl font-bold">Student Management System</h2>
<p class="text-sm text-gray-500">Put your school email here</p>
</div>

<div class="text-right">

<! BACK BUTTON >
<a href="fee_receipts.php"
class="no-print inline-block mb-2 bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
← Back
</a>

<p class="font-bold text-lg">RECEIPT</p>
<p class="text-sm">Date: <?php echo date("d M Y"); ?></p>

</div>

</div>

<! TITLE >
<h1 class="text-3xl font-bold text-center mb-6">
Tuition Fees Receipt
</h1>

<! STUDENT INFO >
<div class="mb-6">

<p><strong>Name:</strong> <?php echo $row['first_name']." ".$row['last_name']; ?></p>
<p><strong>Email:</strong> <?php echo $row['email']; ?></p>
<p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>

</div>

<! TABLE >
<table class="w-full border mb-6">

<tr class="bg-gray-200">
<th class="p-3 border">Description</th>
<th class="p-3 border">Amount</th>
</tr>

<tr>
<td class="p-3 border">Tuition Fee</td>
<td class="p-3 border"><?php echo $total_fee; ?> DKK</td>
</tr>

<tr>
<td class="p-3 border">Amount Paid</td>
<td class="p-3 border text-green-600"><?php echo $paid; ?> DKK</td>
</tr>

<tr>
<td class="p-3 border">Pending</td>
<td class="p-3 border text-red-600"><?php echo $pending; ?> DKK</td>
</tr>

</table>

<! STATUS >
<p class="font-bold text-lg">
Status: 
<?php
echo ($pending <= 0) 
? "<span class='text-green-600'>Paid</span>" 
: "<span class='text-red-600'>Pending</span>";
?>
</p>

<! SIGNATURE >
<div class="flex justify-between mt-10">

<div>
<hr class="w-40">
<p class="text-sm">Accounts</p>
</div>

<div>
<hr class="w-40">
<p class="text-sm">Stamp</p>
</div>

</div>

<!BUTTON >
<div class="text-center mt-8 no-print">

<button onclick="window.print()"
class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
Download / Print
</button>

</div>

</div>

</body>
</html>