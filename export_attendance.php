<?php
include("database.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=attendance_report.xls");

echo "Student Name\tPresent Days\tLate Days\tAbsent Days\tTotal Days\tAttendance %\n";

$query = "
SELECT 
s.first_name,
s.last_name,
COUNT(CASE WHEN a.status='Present' THEN 1 END),
COUNT(CASE WHEN a.status='Late'    THEN 1 END),
COUNT(CASE WHEN a.status='Absent'  THEN 1 END),
COUNT(a.status),
IF(COUNT(a.status)=0,0,
ROUND(
(COUNT(CASE WHEN a.status='Present' THEN 1 END)
+
(COUNT(CASE WHEN a.status='Late' THEN 1 END) * 0.5))
*100 / COUNT(a.status),2)
)
FROM students s
LEFT JOIN attendance a ON a.student_id = s.student_id
GROUP BY s.student_id
";

$result = mysqli_query($conn,$query);

while($row = mysqli_fetch_array($result)){
    echo $row[0]." ".$row[1]."\t".$row[2]."\t".$row[3]."\t".$row[4]."\t".$row[5]."\t".$row[6]."%\n";
}
?>