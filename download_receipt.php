<?php
include("database.php");

$id = $_GET['id'];

$query = "
SELECT 
students.first_name,
students.last_name,
SUM(fees.amount) as paid
FROM fees
JOIN students
ON fees.student_id = students.student_id
WHERE students.student_id='$id'
";

$result = mysqli_query($conn,$query);
$row = mysqli_fetch_assoc($result);

$total_fee = 10000;
$paid = $row['paid'];

if(!$paid){
    $paid = 0;
}

$pending = $total_fee - $paid;
$status  = $pending <= 0 ? 'PAID' : 'PENDING';
$name    = $row['first_name'].' '.$row['last_name'];
$date    = date("d M Y");

// ── TCPDF 
require_once('TCPDF/tcpdf.php');

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Document info
$pdf->SetCreator('Student Management System');
$pdf->SetAuthor('Montessori Pre-1');
$pdf->SetTitle('Fee Receipt – '.$name);

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Margins
$pdf->SetMargins(20, 20, 20);
$pdf->AddPage();

// ── HEADER BAND 
$pdf->SetFillColor(29, 78, 216);   // blue-700
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 18);
$pdf->Cell(0, 14, 'Student Management System', 0, 1, 'C', true);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 8, 'Official Fee Receipt – Montessori Pre-1', 0, 1, 'C', true);
$pdf->Ln(6);

// ── STUDENT DETAILS 
$pdf->SetTextColor(30, 30, 30);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(50, 8, 'Student Name:', 0, 0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0,  8, $name, 0, 1);

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(50, 8, 'Date:', 0, 0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0,  8, $date, 0, 1);

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(50, 8, 'Academic Fee:', 0, 0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0,  8, '10,000 DKK', 0, 1);
$pdf->Ln(4);

// ── FEE TABLE HEADER 
$pdf->SetFillColor(220, 220, 220);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(47, 10, 'Total Fee',    1, 0, 'C', true);
$pdf->Cell(47, 10, 'Amount Paid',  1, 0, 'C', true);
$pdf->Cell(47, 10, 'Pending',      1, 0, 'C', true);
$pdf->Cell(29, 10, 'Status',       1, 1, 'C', true);

// ── FEE TABLE ROW 
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(47, 10, '10,000 DKK',          1, 0, 'C');

// Paid in green
$pdf->SetTextColor(22, 163, 74);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(47, 10, number_format($paid).' DKK', 1, 0, 'C');

// Pending in red
$pdf->SetTextColor(220, 38, 38);
$pdf->Cell(47, 10, number_format($pending).' DKK', 1, 0, 'C');

// Status badge colour
if($pending <= 0){
    $pdf->SetTextColor(22, 163, 74);
} else {
    $pdf->SetTextColor(220, 38, 38);
}
$pdf->Cell(29, 10, $status, 1, 1, 'C');

$pdf->Ln(12);

// ── SIGNATURE LINE 
$pdf->SetTextColor(30, 30, 30);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(85, 6, '_______________________________', 0, 0, 'C');
$pdf->Cell(0,  6, '_______________________________', 0, 1, 'C');
$pdf->Cell(85, 6, 'Accounts Office',                0, 0, 'C');
$pdf->Cell(0,  6, 'Official Stamp',                 0, 1, 'C');
$pdf->Ln(8);

// ── FOOTER NOTE 
$pdf->SetFont('helvetica', 'I', 9);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(0, 6, 'This is a system-generated receipt. Montessori Pre-1 · Student Management System', 0, 1, 'C');

// ── OUTPUT AS DOWNLOAD 
$filename = 'Fee_Receipt_'.str_replace(' ','_',$name).'_'.$date.'.pdf';
$pdf->Output($filename, 'D');
exit();
?>