<?php
include("database.php");

// GET LOW ATTENDANCE STUDENTS (include student_id and email)
$query = "
SELECT
s.student_id,
s.first_name,
s.last_name,
s.email,
IF(COUNT(a.status)=0,0,
ROUND(
(COUNT(CASE WHEN a.status='Present' THEN 1 END)
+
(COUNT(CASE WHEN a.status='Late' THEN 1 END) * 0.5))
*100 / COUNT(a.status),2)
) AS percentage
FROM students s
LEFT JOIN attendance a ON s.student_id = a.student_id
GROUP BY s.student_id
HAVING percentage < 75
ORDER BY percentage ASC
";

$result = mysqli_query($conn,$query);
$students = array();
while($r = mysqli_fetch_assoc($result)){
    $students[] = $r;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance Alerts</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
<div class="flex">
<?php include("sidebar.php"); ?>

<div class="flex-1 p-10">

  <!HEADER >
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Low Attendance Alerts</h1>
    <div class="space-x-3">
      <a href="send_alert.php"
         class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">
        Send All Alerts (<?php echo count($students); ?>)
      </a>
      <a href="dashboard.php"
         class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Back to Dashboard
      </a>
    </div>
  </div>

  <! TOAST NOTIFICATION (hidden by default) >
  <div id="toast" class="hidden fixed top-5 right-5 z-50 px-6 py-3 rounded shadow-lg text-white font-semibold text-sm"></div>

  <?php if(count($students) === 0): ?>
  <div class="bg-green-50 border border-green-200 text-green-700 p-6 rounded text-center text-lg">
    All students have attendance above 75%. No alerts needed!
  </div>
  <?php else: ?>

  <!-- TABLE -->
  <div class="bg-white rounded shadow overflow-hidden">
  <table class="w-full">
    <tr class="bg-red-100">
      <th class="p-3 text-left">Student Name</th>
      <th class="p-3 text-left">Email</th>
      <th class="p-3 text-left">Attendance %</th>
      <th class="p-3 text-center">Status</th>
      <th class="p-3 text-center">Action</th>
    </tr>

    <?php foreach($students as $row): ?>
    <tr class="border-t" id="row-<?php echo $row['student_id']; ?>">

      <td class="p-3 font-semibold">
        <?php echo htmlspecialchars($row['first_name'].' '.$row['last_name']); ?>
      </td>

      <td class="p-3 text-gray-500 text-sm">
        <?php echo htmlspecialchars($row['email'] ? $row['email'] : '—'); ?>
      </td>

      <td class="p-3 font-bold text-red-600">
        <?php echo $row['percentage']; ?>%
      </td>

      <td class="p-3 text-center">
        <span class="bg-red-600 text-white px-2 py-1 rounded text-sm">
          WARNING
        </span>
      </td>

      <td class="p-3 text-center">
        <?php if(empty($row['email'])): ?>
          <span class="text-yellow-600 text-sm font-semibold">No email</span>
        <?php else: ?>
          <button
            class="send-btn bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm"
            data-id="<?php echo $row['student_id']; ?>"
            data-name="<?php echo htmlspecialchars($row['first_name'].' '.$row['last_name']); ?>">
            Send Alert
          </button>
        <?php endif; ?>
      </td>

    </tr>
    <?php endforeach; ?>
  </table>
  </div>

  <?php endif; ?>

</div>
</div>

<script>
// Per-student AJAX send
document.querySelectorAll('.send-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        var id   = this.dataset.id;
        var name = this.dataset.name;
        var self = this;

        self.disabled = true;
        self.textContent = 'Sending…';

        var fd = new FormData();
        fd.append('send_single', '1');
        fd.append('student_id', id);

        fetch('send_alert.php', { method: 'POST', body: fd })
        .then(function(r){ return r.json(); })
        .then(function(data){
            showToast(data.message, data.status === 'success' ? 'green' : 'red');
            if(data.status === 'success'){
                self.textContent = 'Sent';
                self.classList.remove('bg-blue-500','hover:bg-blue-600');
                self.classList.add('bg-green-500');
            } else {
                self.disabled = false;
                self.textContent = 'Retry';
            }
        })
        .catch(function(){
            showToast('Network error. Please try again.', 'red');
            self.disabled = false;
            self.textContent = 'Retry';
        });
    });
});

function showToast(msg, color){
    var t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'fixed top-5 right-5 z-50 px-6 py-3 rounded shadow-lg text-white font-semibold text-sm '
        + (color === 'green' ? 'bg-green-600' : 'bg-red-600');
    setTimeout(function(){ t.classList.add('hidden'); }, 4000);
}
</script>
</body>
</html>