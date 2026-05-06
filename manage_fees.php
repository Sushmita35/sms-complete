<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit(); }

include("database.php");

$total_fee = 10000;

// INSERT PAYMENT - prepared statement
if(isset($_POST['submit']))
{
$student_id = intval($_POST['student_id']);
$amount     = intval($_POST['amount']);
$date       = date("Y-m-d");

$stmt = mysqli_prepare($conn, "INSERT INTO fees(student_id,amount,date) VALUES(?,?,?)");
mysqli_stmt_bind_param($stmt, "iis", $student_id, $amount, $date);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: manage_fees.php");
exit();
}

$students = mysqli_query($conn,"SELECT * FROM students");
?>

<!DOCTYPE html>
<html>

<head>

<title>Fee Management</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-blue-50">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<div class="flex justify-between items-center mb-6">

<h2 class="text-3xl font-bold">
Fee Management
</h2>

<a href="dashboard.php"
class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
← Back to Dashboard
</a>

</div>

<table class="w-full bg-white shadow rounded">

<tr class="bg-blue-200">

<th class="p-3">Student Name</th>
<th class="p-3">Total Fee</th>
<th class="p-3">Paid</th>
<th class="p-3">Pending</th>
<th class="p-3">Add Payment</th>

</tr>

<?php while($row=mysqli_fetch_assoc($students)){

$student_id = intval($row['student_id']);

// GET PAID AMOUNT - prepared statement
$stmt = mysqli_prepare($conn, "SELECT SUM(amount) as total_paid FROM fees WHERE student_id=?");
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$paid_row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$paid = $paid_row['total_paid'];

if(!$paid)
{
$paid = 0;
}

$pending = $total_fee - $paid;

?>

<tr class="border-t">

<td class="p-3">
<?php echo htmlspecialchars($row['first_name']." ".$row['last_name']); ?>
</td>

<td class="p-3">
<?php echo $total_fee; ?> DKK
</td>

<td class="p-3 text-green-600 font-bold">
<?php echo $paid; ?> DKK
</td>

<td class="p-3 text-red-600 font-bold">
<?php echo $pending; ?> DKK
</td>

<td class="p-3">

<form method="POST" class="flex gap-2">

<input type="hidden"
name="student_id"
value="<?php echo $student_id; ?>">

<input
type="number"
name="amount"
placeholder="Amount"
class="border p-2 rounded w-24"
required>

<button
type="submit"
name="submit"
class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">

Save

</button>

</form>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>

</html>