<?php
require_once('lib/tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, "GNITS JEE Admission Receipt\n");
$pdf->Write(0, "Reference No: $reference_no\n");
$pdf->Write(0, "Payment ID: $payment_id\n");

$file = "receipts/$reference_no.pdf";
$pdf->Output($file, 'F');

$_SESSION['pdf_file'] = $file;
?>
