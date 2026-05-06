<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit(); }

include("database.php");

if(isset($_POST['add']))
{
$first=$_POST['first'];
$last=$_POST['last'];
$email=$_POST['email'];
$phone=$_POST['phone'];

// PREPARED STATEMENT - fixes SQL injection
$stmt = mysqli_prepare($conn, "INSERT INTO students(first_name,last_name,email,phone) VALUES(?,?,?,?)");
mysqli_stmt_bind_param($stmt, "ssss", $first, $last, $email, $phone);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$message="Student added successfully";
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add Student</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<h2 class="text-3xl font-bold mb-6">
Add Student
</h2>

<a href="view_students.php"
class="text-blue-600 hover:underline mb-4 inline-block">
← Back to Students
</a>

<div class="bg-white p-8 rounded-lg shadow-md w-96">

<?php
if(isset($message))
{
echo "<p class='text-green-600 mb-4'>$message</p>";
}
?>

<form method="POST" class="space-y-4">

<div>
<label class="block mb-1 font-medium">First Name</label>
<input type="text" name="first" class="w-full border p-2 rounded" required>
</div>

<div>
<label class="block mb-1 font-medium">Last Name</label>
<input type="text" name="last" class="w-full border p-2 rounded" required>
</div>

<div>
<label class="block mb-1 font-medium">Email</label>
<input type="email" name="email" class="w-full border p-2 rounded" required>
</div>

<div>
<label class="block mb-1 font-medium">Phone</label>
<input type="text" name="phone" class="w-full border p-2 rounded" required>
</div>

<button
name="add"
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">

Add Student

</button>

</form>

</div>

</div>

</div>

</body>

</html>