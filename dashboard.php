<?php
session_start();
include("database.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// TOTAL STUDENTS
$total_students = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM students")
)['total'];

// TOTAL FEES
$total_fees = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT SUM(amount) as total FROM fees")
)['total'] ?? 0;

// LOW ATTENDANCE (WITH LATE WEIGHT)
$low_query = "
SELECT 
s.student_id,
s.first_name,
s.last_name,

IF(COUNT(a.status)=0,0,
ROUND(
(
COUNT(CASE WHEN a.status='Present' THEN 1 END)
+
(COUNT(CASE WHEN a.status='Late' THEN 1 END) * 0.5)
)
*100 / COUNT(a.status),2)
) AS percentage

FROM students s
LEFT JOIN attendance a ON s.student_id = a.student_id
GROUP BY s.student_id
HAVING percentage < 75
";

$low_result = mysqli_query($conn, $low_query);
$low_count = mysqli_num_rows($low_result);

// ATTENDANCE SUMMARY
$att = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
COUNT(CASE WHEN status='Present' THEN 1 END) as present,
COUNT(CASE WHEN status='Late' THEN 1 END) as late,
COUNT(*) as total
FROM attendance
"));

$present = $att['present'] ?? 0;
$late = $att['late'] ?? 0;
$total = $att['total'] ?? 0;

if($total == 0){
    $total = 1;
}

$absent = $total - ($present + $late);
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

<div class="mb-8 flex justify-between items-center">

<div>
<h1 class="text-3xl font-bold">Dashboard</h1>
<p class="text-gray-600">Student Management Overview</p>
</div>

<div class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-full font-semibold">
Montessori Pre-1
</div>

</div>

<div class="grid grid-cols-3 gap-6 mb-10">

<div class="bg-blue-600 text-white p-6 rounded shadow">
<h2>Total Students</h2>
<p class="text-4xl"><?php echo $total_students; ?></p>
</div>

<div class="bg-green-600 text-white p-6 rounded shadow">
<h2>Total Fees</h2>
<p class="text-4xl"><?php echo number_format($total_fees); ?> DKK</p>
</div>

<div class="bg-red-600 text-white p-6 rounded shadow">
<h2>Low Attendance</h2>
<p class="text-4xl"><?php echo $low_count; ?></p>
<a href="low_attendance.php" class="text-sm underline mt-2 inline-block">
View Details
</a>
</div>

</div>

<div class="grid grid-cols-3 gap-6">

<div class="bg-white p-6 rounded shadow">
<h2>Manage Students</h2>

<a href="add_student.php" class="block mt-3 bg-green-600 text-white p-2 rounded text-center">
Add Student
</a>

<a href="view_students.php" class="block mt-2 bg-blue-600 text-white p-2 rounded text-center">
Edit Students
</a>
</div>

<div class="bg-white p-6 rounded shadow">
<h2>Fee Receipts</h2>

<a href="fee_receipts.php" class="block mt-3 bg-purple-600 text-white p-2 rounded text-center">
Open
</a>
</div>

<div class="bg-white p-6 rounded shadow">

<h2>Student Analytics</h2>

<form method="GET" action="student_analytics.php" class="mt-3">

<select name="id" class="w-full border p-2 rounded mb-2" required>

<option value="">Select Student</option>

<?php
$res = mysqli_query($conn,"SELECT * FROM students");
while($s=mysqli_fetch_assoc($res)){
echo "<option value='".$s['student_id']."'>".$s['first_name']." ".$s['last_name']."</option>";
}
?>

</select>

<button type="submit" class="w-full bg-indigo-600 text-white p-2 rounded">
View Report
</button>

</form>

</div>

</div>

<div class="bg-white p-6 rounded shadow mt-10 text-center">

<h2 class="text-lg font-semibold mb-2">
Complete Class Attendance
</h2>

<div class="flex justify-center">
<div style="width:200px; height:200px;">
<canvas id="pieChart"></canvas>
</div>
</div>

</div>

</div>

</div>

<script>
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Present', 'Late', 'Absent'],
        datasets: [{
            data: [<?php echo $present; ?>, <?php echo $late; ?>, <?php echo $absent; ?>],
            backgroundColor: ['#22c55e', '#facc15', '#ef4444']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>

</body>
</html>