<?php
include("database.php");

// ── SUMMARY STATS 
$total_students = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as n FROM students"))['n'];

$att = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT
  COUNT(CASE WHEN status='Present' THEN 1 END) as present,
  COUNT(CASE WHEN status='Late'    THEN 1 END) as late,
  COUNT(*) as total
FROM attendance
"));
$att_pct = $att['total'] > 0
    ? round((($att['present'] + ($att['late'] * 0.5)) / $att['total']) * 100, 1)
    : 0;

$fees = mysqli_fetch_assoc(mysqli_query($conn,"SELECT IFNULL(SUM(amount),0) as total FROM fees"));
$total_fees = $fees['total'];

$low_att = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as n FROM (
  SELECT s.student_id,
  IF(COUNT(a.status)=0,0,
     ROUND(
     (COUNT(CASE WHEN a.status='Present' THEN 1 END)
     + (COUNT(CASE WHEN a.status='Late' THEN 1 END) * 0.5))
     *100 / COUNT(a.status),2)
  ) AS pct
  FROM students s
  LEFT JOIN attendance a ON s.student_id = a.student_id
  GROUP BY s.student_id
  HAVING pct < 75
) x
"))['n'];

// ── PER-STUDENT ATTENDANCE REPORT 
$att_rows = mysqli_query($conn,"
SELECT s.student_id, s.first_name, s.last_name, s.email,
COUNT(CASE WHEN a.status='Present' THEN 1 END) AS present_days,
COUNT(CASE WHEN a.status='Late'    THEN 1 END) AS late_days,
COUNT(CASE WHEN a.status='Absent'  THEN 1 END) AS absent_days,
COUNT(a.status) AS total_days,
IF(COUNT(a.status)=0,0,
   ROUND(
   (COUNT(CASE WHEN a.status='Present' THEN 1 END)
   + (COUNT(CASE WHEN a.status='Late' THEN 1 END) * 0.5))
   *100 / COUNT(a.status),2)
) AS pct
FROM students s
LEFT JOIN attendance a ON s.student_id = a.student_id
GROUP BY s.student_id
ORDER BY pct ASC
");

// ── RESULTS REPORT
$res_rows = mysqli_query($conn,"
SELECT s.first_name, s.last_name,
r.class, r.subject, r.marks, r.grade, r.status
FROM results r
JOIN students s ON r.student_id = s.student_id
ORDER BY r.marks DESC
");

// ── FEES REPORT 
$fee_rows = mysqli_query($conn,"
SELECT s.first_name, s.last_name,
IFNULL(SUM(f.amount),0) AS paid,
(10000 - IFNULL(SUM(f.amount),0)) AS pending
FROM students s
LEFT JOIN fees f ON f.student_id = s.student_id
GROUP BY s.student_id
ORDER BY pending DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  @media print {
    .no-print { display: none !important; }
    .page-break { page-break-before: always; }
  }
</style>
</head>
<body class="bg-gray-100">
<div class="flex">
<?php include("sidebar.php"); ?>

<div class="flex-1 p-10" id="report-body">

  <!HEADER >
  <div class="flex justify-between items-center mb-6 no-print">
    <div>
      <h1 class="text-2xl font-bold">Reports</h1>
      <p class="text-gray-500 text-sm">Generated: <?php echo date("d M Y, h:i A"); ?></p>
    </div>
    <div class="space-x-3">
      <button onclick="window.print()"
        class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
        Print / Save PDF
      </button>
      <a href="dashboard.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Back to Dashboard
      </a>
    </div>
  </div>

  <! SUMMARY CARDS >
  <div class="grid grid-cols-4 gap-5 mb-10">
    <div class="bg-blue-600 text-white p-5 rounded shadow text-center">
      <p class="text-3xl font-bold"><?php echo $total_students; ?></p>
      <p class="text-sm mt-1">Total Students</p>
    </div>
    <div class="bg-green-600 text-white p-5 rounded shadow text-center">
      <p class="text-3xl font-bold"><?php echo $att_pct; ?>%</p>
      <p class="text-sm mt-1">Overall Attendance</p>
    </div>
    <div class="bg-red-600 text-white p-5 rounded shadow text-center">
      <p class="text-3xl font-bold"><?php echo $low_att; ?></p>
      <p class="text-sm mt-1">Low Attendance Students</p>
    </div>
    <div class="bg-purple-600 text-white p-5 rounded shadow text-center">
      <p class="text-3xl font-bold"><?php echo number_format($total_fees); ?></p>
      <p class="text-sm mt-1">Total Fees Collected (DKK)</p>
    </div>
  </div>

  <!ATTENDANCE REPORT >
  <div class="bg-white rounded shadow p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Attendance Report</h2>
      <span class="text-sm text-gray-500">All Students</span>
    </div>

    <table class="w-full text-sm">
      <thead>
        <tr class="bg-blue-100">
          <th class="p-3 text-left">#</th>
          <th class="p-3 text-left">Student</th>
          <th class="p-3 text-left">Email</th>
          <th class="p-3 text-center">Present</th>
          <th class="p-3 text-center">Late</th>
          <th class="p-3 text-center">Absent</th>
          <th class="p-3 text-center">Total Days</th>
          <th class="p-3 text-center">Attendance %</th>
          <th class="p-3 text-center">Status</th>
        </tr>
      </thead>
      <tbody>
      <?php $i=1; while($row=mysqli_fetch_assoc($att_rows)): ?>
        <tr class="border-t hover:bg-gray-50">
          <td class="p-3 text-gray-400"><?php echo $i++; ?></td>
          <td class="p-3 font-semibold"><?php echo htmlspecialchars($row['first_name'].' '.$row['last_name']); ?></td>
          <td class="p-3 text-gray-500 text-xs"><?php echo htmlspecialchars($row['email'] ?: '—'); ?></td>
          <td class="p-3 text-center text-green-700 font-semibold"><?php echo $row['present_days']; ?></td>
          <td class="p-3 text-center text-yellow-600 font-semibold"><?php echo $row['late_days']; ?></td>
          <td class="p-3 text-center text-red-600 font-semibold"><?php echo $row['absent_days']; ?></td>
          <td class="p-3 text-center"><?php echo $row['total_days']; ?></td>
          <td class="p-3 text-center font-bold <?php echo $row['pct'] < 75 ? 'text-red-600' : 'text-green-600'; ?>">
            <?php echo $row['pct']; ?>%
          </td>
          <td class="p-3 text-center">
            <?php if($row['pct'] < 75): ?>
              <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold">LOW</span>
            <?php else: ?>
              <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold">OK</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!RESULTS REPORT >
  <div class="bg-white rounded shadow p-6 mb-8 page-break">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Academic Results Report</h2>
      <span class="text-sm text-gray-500">All Results</span>
    </div>

    <table class="w-full text-sm">
      <thead>
        <tr class="bg-green-100">
          <th class="p-3 text-left">#</th>
          <th class="p-3 text-left">Student</th>
          <th class="p-3 text-left">Class</th>
          <th class="p-3 text-left">Subject</th>
          <th class="p-3 text-center">Marks</th>
          <th class="p-3 text-center">Grade</th>
          <th class="p-3 text-center">Status</th>
        </tr>
      </thead>
      <tbody>
      <?php $i=1; while($row=mysqli_fetch_assoc($res_rows)): ?>
        <tr class="border-t hover:bg-gray-50">
          <td class="p-3 text-gray-400"><?php echo $i++; ?></td>
          <td class="p-3 font-semibold"><?php echo htmlspecialchars($row['first_name'].' '.$row['last_name']); ?></td>
          <td class="p-3"><?php echo htmlspecialchars($row['class']); ?></td>
          <td class="p-3"><?php echo htmlspecialchars($row['subject']); ?></td>
          <td class="p-3 text-center font-bold"><?php echo $row['marks']; ?></td>
          <td class="p-3 text-center font-bold text-blue-600"><?php echo $row['grade']; ?></td>
          <td class="p-3 text-center font-bold <?php echo $row['status']==='PASS' ? 'text-green-600' : 'text-red-600'; ?>">
            <?php echo $row['status']; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <! FEES REPORT >
  <div class="bg-white rounded shadow p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Fees Collection Report</h2>
      <span class="text-sm text-gray-500">Total Fee per student: 10,000 DKK</span>
    </div>

    <table class="w-full text-sm">
      <thead>
        <tr class="bg-purple-100">
          <th class="p-3 text-left">#</th>
          <th class="p-3 text-left">Student</th>
          <th class="p-3 text-center">Paid (DKK)</th>
          <th class="p-3 text-center">Pending (DKK)</th>
          <th class="p-3 text-center">Payment Status</th>
        </tr>
      </thead>
      <tbody>
      <?php $i=1; while($row=mysqli_fetch_assoc($fee_rows)): ?>
        <tr class="border-t hover:bg-gray-50">
          <td class="p-3 text-gray-400"><?php echo $i++; ?></td>
          <td class="p-3 font-semibold"><?php echo htmlspecialchars($row['first_name'].' '.$row['last_name']); ?></td>
          <td class="p-3 text-center text-green-700 font-bold"><?php echo number_format($row['paid']); ?></td>
          <td class="p-3 text-center <?php echo $row['pending'] > 0 ? 'text-red-600 font-bold' : 'text-gray-400'; ?>">
            <?php echo number_format($row['pending']); ?>
          </td>
          <td class="p-3 text-center">
            <?php if($row['pending'] <= 0): ?>
              <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold">PAID</span>
            <?php elseif($row['paid'] > 0): ?>
              <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-xs font-bold">PARTIAL</span>
            <?php else: ?>
              <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold">UNPAID</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!FOOTER (shows on print) >
  <div class="text-center text-xs text-gray-400 mt-6">
    Montessori Pre-1 · Student Management System · <?php echo date("d M Y"); ?>
  </div>

</div>
</div>
</body>
</html>