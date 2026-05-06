<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit(); }

include("database.php");

// FIX ID
$id = intval($_GET['id']);

// FETCH STUDENT DATA - prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE student_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

if(!$result){
    die("Fetch Error: ".mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// UPDATE DATA - prepared statement
if(isset($_POST['update'])){

    $first = $_POST['first'];
    $last  = $_POST['last'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = mysqli_prepare($conn, "
    UPDATE students SET
    first_name=?,
    last_name=?,
    email=?,
    phone=?
    WHERE student_id=?
    ");
    mysqli_stmt_bind_param($stmt, "ssssi", $first, $last, $email, $phone, $id);

    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        header("Location: view_students.php");
        exit();
    }else{
        echo "<p style='color:red;'>Update Error: ".mysqli_stmt_error($stmt)."</p>";
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
<title>Edit Student</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">

<h2 class="text-2xl font-bold mb-4">Edit Student</h2>

<form method="POST">

<div class="mb-3">
<label>First Name</label>
<input type="text" name="first"
value="<?php echo htmlspecialchars($row['first_name']); ?>"
class="w-full border p-2 rounded" required>
</div>

<div class="mb-3">
<label>Last Name</label>
<input type="text" name="last"
value="<?php echo htmlspecialchars($row['last_name']); ?>"
class="w-full border p-2 rounded" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email"
value="<?php echo htmlspecialchars($row['email']); ?>"
class="w-full border p-2 rounded" required>
</div>

<div class="mb-3">
<label>Phone</label>
<input type="text" name="phone"
value="<?php echo htmlspecialchars($row['phone']); ?>"
class="w-full border p-2 rounded" required>
</div>

<button type="submit" name="update"
class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
Update Student
</button>

</form>

<a href="view_students.php" class="text-blue-500 mt-3 inline-block">
← Back
</a>

</div>

</body>

</html>