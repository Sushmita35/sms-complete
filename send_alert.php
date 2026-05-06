<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

include("database.php");

// ─── SMTP CONFIG 
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_USER',     'systemstudentmanagement216@gmail.com');
define('SMTP_PASS',     'mfmkfixangvrrcru');
define('SMTP_PORT',     587);
define('SENDER_NAME',   'Montessori Pre-1');

// ─── HELPER: send one email 
function sendAttendanceAlert($email, $first, $last, $percentage) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USER, SENDER_NAME);
        $mail->addAddress($email, $first . ' ' . $last);

        $mail->isHTML(true);
        $mail->Subject = '⚠️ Attendance Warning – ' . $first . ' ' . $last;
        $mail->Body = "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;
                     border:1px solid #e5e7eb;border-radius:8px;overflow:hidden'>

          <div style='background:#1d4ed8;color:#fff;padding:24px 32px'>
            <h2 style='margin:0'>Attendance Warning</h2>
            <p style='margin:4px 0 0;font-size:14px;opacity:.85'>Montessori Pre-1 – Student Management System</p>
          </div>

          <div style='padding:28px 32px'>
            <p style='font-size:16px'>Dear <strong>{$first} {$last}</strong>,</p>
            <p>This is an automated notice regarding your attendance record.</p>
            <div style='background:#fef2f2;border-left:4px solid #dc2626;
                        padding:16px 20px;border-radius:4px;margin:20px 0'>
              <p style='margin:0;font-size:18px;color:#dc2626;font-weight:700'>
                Current Attendance: {$percentage}%
              </p>
              <p style='margin:6px 0 0;color:#6b7280;font-size:14px'>
                Minimum required attendance: <strong>75%</strong>
              </p>
            </div>
            <p>Your attendance has fallen below the required threshold.
               Please ensure regular attendance to avoid academic penalties.</p>
            <p style='margin-top:28px;color:#6b7280;font-size:13px'>
              This is an automated message. Please do not reply to this email.<br>
              Contact the administration office for further assistance.
            </p>
          </div>

          <div style='background:#f9fafb;padding:14px 32px;font-size:12px;color:#9ca3af'>
            © Montessori Pre-1 · Student Management System
          </div>
        </div>";

        $mail->AltBody = "Dear {$first} {$last},\n\nYour attendance is {$percentage}%.\nMinimum required is 75%.\n\nPlease improve your attendance.\n\nMontessori Pre-1";

        $mail->send();
        return array('ok' => true);

    } catch (Exception $e) {
        return array('ok' => false, 'error' => $mail->ErrorInfo);
    }
}

// ─── MODE 1: SINGLE STUDENT (AJAX from low_attendance.php) 
if (isset($_POST['send_single'])) {
    $student_id = intval($_POST['student_id']);

    $row = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT s.first_name, s.last_name, s.email,
        IF(COUNT(a.status)=0, 0,
           ROUND(
           (COUNT(CASE WHEN a.status='Present' THEN 1 END)
           + (COUNT(CASE WHEN a.status='Late' THEN 1 END) * 0.5))
           *100 / COUNT(a.status), 2)
        ) AS percentage
        FROM students s
        LEFT JOIN attendance a ON s.student_id = a.student_id
        WHERE s.student_id = '$student_id'
        GROUP BY s.student_id
    "));

    if (!$row || empty($row['email'])) {
        $result_msg    = 'error';
        $result_detail = 'Student not found or no email address on record.';
    } else {
        $res = sendAttendanceAlert($row['email'], $row['first_name'], $row['last_name'], $row['percentage']);
        $result_msg    = $res['ok'] ? 'success' : 'error';
        $result_detail = $res['ok']
            ? "Alert sent to {$row['first_name']} {$row['last_name']} ({$row['email']})"
            : "Failed: " . $res['error'];
    }

    header('Content-Type: application/json');
    echo json_encode(array('status' => $result_msg, 'message' => $result_detail));
    exit();
}

// ─── MODE 2: BULK SEND
$bulk = isset($_POST['send_bulk']);

$result  = mysqli_query($conn, "
SELECT s.student_id, s.first_name, s.last_name, s.email,
IF(COUNT(a.status)=0, 0,
   ROUND(
   (COUNT(CASE WHEN a.status='Present' THEN 1 END)
   + (COUNT(CASE WHEN a.status='Late' THEN 1 END) * 0.5))
   *100 / COUNT(a.status), 2)
) AS percentage
FROM students s
LEFT JOIN attendance a ON s.student_id = a.student_id
GROUP BY s.student_id
HAVING percentage < 75
ORDER BY percentage ASC
");

$students = array();
while ($r = mysqli_fetch_assoc($result)) {
    $students[] = $r;
}

$sent   = 0;
$failed = 0;
$log    = array();

if ($bulk) {
    foreach ($students as $row) {
        if (empty($row['email'])) {
            $log[] = array('status'=>'skip', 'name'=>$row['first_name'].' '.$row['last_name'], 'msg'=>'No email address');
            continue;
        }
        $res = sendAttendanceAlert($row['email'], $row['first_name'], $row['last_name'], $row['percentage']);
        if ($res['ok']) {
            $sent++;
            $log[] = array('status'=>'sent', 'name'=>$row['first_name'].' '.$row['last_name'],
                      'email'=>$row['email'], 'pct'=>$row['percentage']);
        } else {
            $failed++;
            $log[] = array('status'=>'fail', 'name'=>$row['first_name'].' '.$row['last_name'],
                      'email'=>$row['email'], 'msg'=>$res['error']);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Send Attendance Alerts</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex">
<?php include("sidebar.php"); ?>
<div class="flex-1 p-10">

  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Send Attendance Alerts</h1>
    <a href="low_attendance.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Back to Alerts</a>
  </div>

  <?php if ($bulk): ?>
  <div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-green-600 text-white p-6 rounded shadow text-center">
      <p class="text-4xl font-bold"><?php echo $sent; ?></p>
      <p class="mt-1">Emails Sent</p>
    </div>
    <div class="bg-red-600 text-white p-6 rounded shadow text-center">
      <p class="text-4xl font-bold"><?php echo $failed; ?></p>
      <p class="mt-1">Failed</p>
    </div>
    <div class="bg-gray-600 text-white p-6 rounded shadow text-center">
      <p class="text-4xl font-bold"><?php echo count($log); ?></p>
      <p class="mt-1">Total Processed</p>
    </div>
  </div>

  <div class="bg-white rounded shadow p-6 mb-8">
    <h2 class="font-bold text-lg mb-4">Send Log</h2>
    <table class="w-full text-sm">
      <tr class="bg-gray-100">
        <th class="p-2 text-left">Student</th>
        <th class="p-2 text-left">Email</th>
        <th class="p-2 text-left">Attendance</th>
        <th class="p-2 text-left">Result</th>
      </tr>
      <?php foreach ($log as $l): ?>
      <tr class="border-t">
        <td class="p-2"><?php echo htmlspecialchars($l['name']); ?></td>
        <td class="p-2 text-gray-500"><?php echo htmlspecialchars(isset($l['email']) ? $l['email'] : '—'); ?></td>
        <td class="p-2"><?php echo isset($l['pct']) ? $l['pct'].'%' : '—'; ?></td>
        <td class="p2 font-semibold
          <?php echo $l['status']==='sent' ? 'text-green-600' : ($l['status']==='fail' ? 'text-red-600' : 'text-yellow-600'); ?>">
          <?php
            if ($l['status']==='sent')  echo 'Sent';
            elseif ($l['status']==='fail') echo 'Failed: '.htmlspecialchars($l['msg']);
            else echo 'Skipped: '.htmlspecialchars($l['msg']);
          ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <?php else: ?>
  <div class="bg-white rounded shadow p-6 mb-8">
    <h2 class="font-bold text-lg mb-2">Students Below 75% Attendance</h2>
    <p class="text-gray-500 text-sm mb-4"><?php echo count($students); ?> student(s) will receive an alert.</p>

    <?php if (count($students) === 0): ?>
      <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded">
        All students have attendance above 75%. No alerts needed!
      </div>
    <?php else: ?>
    <table class="w-full text-sm mb-6">
      <tr class="bg-red-100">
        <th class="p-3 text-left">Student</th>
        <th class="p-3 text-left">Email</th>
        <th class="p-3 text-left">Attendance %</th>
        <th class="p-3 text-left">Ready</th>
      </tr>
      <?php foreach ($students as $row): ?>
      <tr class="border-t">
        <td class="p-3 font-semibold"><?php echo htmlspecialchars($row['first_name'].' '.$row['last_name']); ?></td>
        <td class="p-3 text-gray-600"><?php echo htmlspecialchars($row['email'] ? $row['email'] : '—'); ?></td>
        <td class="p-3 font-bold text-red-600"><?php echo $row['percentage']; ?>%</td>
        <td class="p-3">
          <?php if (empty($row['email'])): ?>
            <span class="text-yellow-600 font-semibold">No email on record</span>
          <?php else: ?>
            <span class="text-green-600 font-semibold">Ready</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>

    <form method="POST">
      <button type="submit" name="send_bulk"
        class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 font-semibold">
        Send Alerts to All <?php echo count($students); ?> Students
      </button>
    </form>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>
</div>
</body>
</html>