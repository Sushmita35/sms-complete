<?php
include("database.php");

$query = "SELECT * FROM students";
$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>

<head>
<title>View Students</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<div class="flex justify-between items-center mb-6">

<h2 class="text-2xl font-bold">
👨‍🎓 Students List
</h2>

<a href="dashboard.php"
class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
← Back to Dashboard
</a>

</div>

<!POPUP NOTIFICATION >
<?php if(isset($_GET['msg']) && $_GET['msg']=="deleted"){ ?>
<div id="toast"
class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded shadow-lg transition-opacity duration-500">
    ✅ Student deleted successfully!
</div>
<?php } ?>

<! TABLE >
<table class="w-full bg-white shadow rounded overflow-hidden">

<tr class="bg-blue-200 text-left">
<th class="p-3">ID</th>
<th class="p-3">First Name</th>
<th class="p-3">Last Name</th>
<th class="p-3">Email</th>
<th class="p-3">Actions</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr class="border-t hover:bg-gray-50">

<td class="p-3"><?php echo $row['student_id']; ?></td>
<td class="p-3"><?php echo $row['first_name']; ?></td>
<td class="p-3"><?php echo $row['last_name']; ?></td>
<td class="p-3"><?php echo $row['email']; ?></td>

<td class="p-3 space-x-2">

<a href="edit_student.php?id=<?php echo $row['student_id']; ?>"
class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
Edit
</a>

<a href="delete_student.php?id=<?php echo $row['student_id']; ?>"
class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
onclick="return confirm('Are you sure you want to delete this student?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

<!AUTO HIDE SCRIPT >
<script>
setTimeout(() => {
    const toast = document.getElementById("toast");
    if(toast){
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 500);
    }
}, 3000);
</script>

</body>

</html>