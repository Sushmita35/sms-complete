<?php
session_start();
include("database.php");

$query = "
SELECT 
students.student_id,
students.first_name,
students.last_name,
IFNULL(SUM(fees.amount),0) as paid
FROM students
LEFT JOIN fees 
ON students.student_id = fees.student_id
GROUP BY students.student_id
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>

<title>Fee Records</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<a href="dashboard.php"
class="bg-blue-500 text-white px-4 py-2 rounded hover