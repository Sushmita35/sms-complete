<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit(); }

include("database.php");

if(!isset($_GET['id'])){
    die("No ID received");
}

$id = intval($_GET['id']);

// FETCH DATA - prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM results WHERE result_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if(!$res){
    die("Result not found");
}

if(isset($_POST['update'])){

    $class   = $_POST['class'];
    $subject = $_POST['subject'];
    $marks   = intval($_POST['marks']);

    // AUTO GRADE
    if($marks >= 80){ $grade = "A"; }
    elseif($marks >= 70){ $grade = "B"; }
    elseif($marks >= 60){ $grade = "C"; }
    elseif($marks >= 50){ $grade = "D"; }
    else{ $grade = "F"; }

    $status = ($marks >= 40) ? "PASS" : "FAIL";

    // UPDATE - prepared statement
    $stmt = mysqli_prepare($conn,"
    UPDATE results SET
    class=?,
    subject=?,
    marks=?,
    grade=?,
    status=?
    WHERE result_id=?
    ");
    mysqli_stmt_bind_param($stmt, "ssissi", $class, $subject, $marks, $grade, $status, $id);

    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        header("Location: manage_results.php?msg=updated");
        exit();
    } else {
        echo "Update failed: " . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Result</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">

<div class="flex justify-between items-center mb-4">
<h2 class="text-xl font-bold">Edit Result</h2>

<a href="manage_results.php"
class="bg-blue-600 text-white px-3 py-1 rounded">
← Back
</a>
</div>

<form method="POST">

<! CLASS >
<label class="block font-semibold">Class</label>
<input type="text" name="class"
value="<?php echo htmlspecialchars($res['class']); ?>"
class="w-full border p-2 mb-4 rounded" required>

<! SUBJECT >
<label class="block font-semibold">Subject</label>
<input type="text" name="subject"
value="<?php echo htmlspecialchars($res['subject']); ?>"
class="w-full border p-2 mb-4 rounded" required>

<! MARKS >
<label class="block font-semibold">Marks</label>
<input type="number" name="marks"
value="<?php echo $res['marks']; ?>"
class="w-full border p-2 mb-4 rounded" required>

<p class="text-sm text-gray-500 mb-4">
Grade and status will update automatically.
</p>

<button type="submit" name="update"
class="bg-green-600 text-white px-4 py-2 rounded w-full">
Update Result
</button>

</form>

</div>

</body>
</html>V