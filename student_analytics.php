<?php
session_start();
if(!isset($_SESSION['admin'])){ header("Location: login.php"); exit(); }

include("database.php");

if(!isset($_GET['id']) || $_GET['id']==""){
    die("No student selected");
}

$id = intval($_GET['id']);

// STUDENT INFO - prepared statement
$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE student_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$student = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if(!$student){
    die("Student not found");
}

// FEES - prepared statement
$stmt = mysqli_prepare($conn, "SELECT IFNULL(SUM(amount),0) as paid FROM fees WHERE student_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$fees = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$paid = $fees['paid'] ?? 0;
$total_fee = 10000;
$pending = $total_fee - $paid;

// RESULT - prepared statement
$stmt = mysqli_prepare($conn, "SELECT marks, grade, status FROM results WHERE student_id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$marks  = $result['marks']  ?? "No Data";
$grade  = $result['grade']  ?? "-";
$status = $result['status'] ?? "-";

// ATTENDANCE - prepared statement
$stmt = mysqli_prepare($conn, "
SELECT 
COUNT(CASE WHEN status='Present' THEN 1 END) as present,
COUNT(CASE WHEN status='Late' THEN 1 END) as late,
COUNT(*) as total
FROM attendance WHERE student_id=?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$att = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$present = $att['present'] ?? 0;
$late    = $att['late']    ?? 0;
$total   = $att['total']   ?? 0;

// Weighted percentage
$percentage = ($total == 0) ? 0 : round((($present + ($late * 0.5)) / $total) * 100, 2);

$absent = $total - ($present + $late);
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Analytics</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 p-10">

<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">

<!-- HEADER -->
<div class="flex justify-between mb-6">
<h1 class="text-2xl font-bold">
<?php echo $student['first_name']." ".$student['last_name']; ?>
</h1>

<a href="dashboard.php" class="bg-blue-600 text-white px-4 py-2 rounded">
← Back
</a>
</div>

<!-- STUDENT INFO -->
<div class="bg-gray-100 p-4 rounded mb-6">
<h2 class="font-semibold mb-2">Student Information</h2>
<p><strong>Email:</strong> <?php echo $student['email']; ?></p>
<p><strong>Phone:</strong> <?php echo $student['phone']; ?></p>
</div>

<!-- INFO CARDS -->
<div class="grid grid-cols-3 gap-6 mb-6">

<div class="bg-green-100 p-4 rounded">
<h3 class="font-bold">Fees</h3>
<p>Total: <?php echo $total_fee; ?></p>
<p>Paid: <?php echo $paid; ?></p>
<p>Pending: <?php echo $pending; ?></p>
</div>

<div class="bg-blue-100 p-4 rounded">
<h3 class="font-bold">Result</h3>
<p>Marks: <?php echo $marks; ?></p>
<p>Grade: <?php echo $grade; ?></p>
<p>Status: <?php echo $status; ?></p>
</div>

<div class="bg-yellow-100 p-4 rounded">
<h3 class="font-bold">Attendance</h3>
<p>Present: <?php echo $present; ?></p>
<p>Late: <?php echo $late; ?></p>
<p>Absent: <?php echo $absent; ?></p>
<p>Percentage: <?php echo $percentage; ?>%</p>
</div>

</div>

<!-- CHART -->
<div class="mt-4">

<h2 class="text-lg font-semibold text-center mb-2">
Attendance Chart (<?php echo $percentage; ?>%)
</h2>

<div class="flex justify-center">
    <div style="width:200px;">
        <canvas id="chart"></canvas>
    </div>
</div>

</div>

</div>

<script>
new Chart(document.getElementById('chart'), {
    type: 'pie',
    data: {
        labels: ['Present', 'Late', 'Absent'],
        datasets: [{
            data: [<?php echo $present; ?>, <?php echo $late; ?>, <?php echo $absent; ?>],
            backgroundColor: ['#16a34a', '#facc15', '#dc2626']
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
</script>

</body>
</html>