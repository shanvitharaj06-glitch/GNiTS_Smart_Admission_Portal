<?php
require('lib/FPDF-master/fpdf.php');
include "db/db_connect.php";

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM oci WHERE id=$id");
$row = $res->fetch_assoc();

if(!$row){
    die("Record not found");
}

$upload_dir = __DIR__ . "/uploads/";
$upload_url = "http://gnitscollege.in/uploads/";

$pdf = new FPDF();
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();

// ================= HEADER AS IMAGE =================
if(file_exists('pics/collage_header.png')){
    $pdf->Image('pics/collage_header.png', 10, 5, 190, 30);
}

// ================= JEE ID & DATE =================
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(10, 38);
$pdf->Cell(100, 5, "OCI ID: " . $row['reference_no'], 0, 0);
$pdf->Cell(0, 5, date("F d, Y"), 0, 1, 'R');

$pdf->SetFont('Arial','B',12);
$pdf->SetXY(15, 45);

$pdf->MultiCell(0, 6,
"Application for Admission into first year B.Tech course under
Foreign Nationals / Overseas Citizen of India / Children of Indian Workers in Gulf Countries",
0, 'C');

// 👇 VERY IMPORTANT
$y = $pdf->GetY();   // get current Y after heading

// Move a little down
$y += 5;

$pdf->MultiCell(0, 6, "for the A.Y 2025-26", 0, 'C');

// ================= PHOTO =================
$photoX = 155;
$photoY = $y;
$photoW = 35;
$photoH = 42;

$photoPath = __DIR__ . "/uploads/" . basename($row['photo']);

if(!empty($row['photo']) && file_exists($photoPath)){
    $pdf->Image($photoPath, $photoX, $photoY, $photoW, $photoH);
}
$pdf->SetFont('Arial','B',7);
$pdf->SetXY($photoX - 5, $photoY + $photoH + 1);
$pdf->Cell($photoW + 10, 4, "Upload Latest Passport", 0, 1, 'C');
$pdf->SetXY($photoX - 5, $photoY + $photoH + 5);
$pdf->Cell($photoW + 10, 4, "Size Colour Photograph", 0, 1, 'C');

// ================= DETAILS =================
$y = $photoY + 2;

function labelValueRow($pdf, $label, $value, &$y, $labelWidth=60){
    $pdf->SetFont('Arial','B',9);
    $pdf->SetXY(10, $y);
    $pdf->Cell($labelWidth, 7, $label);
    $pdf->SetFont('Arial','',9);
    $pdf->SetXY(10 + $labelWidth, $y);
    $pdf->Cell(80, 7, $value);
    $y += 8;
}

labelValueRow($pdf, "Name of the Applicant", $row['name'], $y);
labelValueRow($pdf, "Date of Birth", $row['dob'], $y);
labelValueRow($pdf, "Father's Name", $row['father'], $y);
labelValueRow($pdf, "Mother's Name", $row['mother'], $y);
labelValueRow($pdf, "Mobile No.", $row['mobile'], $y);
labelValueRow($pdf, "Aadhar (UID) No.", $row['aadhar'], $y);

// ================= ADDRESS =================
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(10, $y);
$pdf->MultiCell(60, 5, "Address for Communication (Block Letters)");
$y_label_end = $pdf->GetY();

$pdf->SetFont('Arial','',10);
$pdf->SetXY(70, $y);
$pdf->MultiCell(120, 5,
    $row['address1'].", ".$row['address2']."\n".
    $row['city'].", ".$row['state']." ".$row['zip']."\n".
    $row['country']
);
$y = max($y_label_end, $pdf->GetY());
$y += 2;

// ================= EMAIL =================
labelValueRow($pdf, "Email Address", $row['email'], $y);

// ================= LINE SEPARATOR =================
$pdf->Line(10, $y, 200, $y);
$y += 3;

// ================= EDUCATION =================
labelValueRow($pdf, "Name of the Board", $row['board'], $y);
labelValueRow($pdf, "Month & Year of passing", $row['passing_year'], $y);

// Total Marks / Group Marks on same line
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(10, $y);
$pdf->Cell(30, 7, "Total Marks");
$pdf->SetFont('Arial','',9);
$pdf->SetXY(40, $y);
$pdf->Cell(30, 7, $row['total_marks']);

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(100, $y);
$pdf->Cell(40, 7, "Total Group Marks");
$pdf->SetFont('Arial','',9);
$pdf->SetXY(145, $y);
$pdf->Cell(30, 7, $row['group_marks']);
$y += 8;

// NRI Relation details
 

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(100, $y);
$pdf->Cell(55, 7, "Place/Country");
$pdf->SetFont('Arial','',9);
$pdf->SetXY(165, $y);
$pdf->Cell(30, 7, $row['place_country']);
$y += 8;

 

// ================= LINE SEPARATOR =================
$pdf->Line(10, $y, 200, $y);
$y += 4;

// ================= DOCUMENTS (side by side) =================
function docPair($pdf, $title1, $file1, $title2, $file2, &$y, $upload_url){
    $pdf->SetFont('Arial','B',9);
    $pdf->SetXY(10, $y);
    $pdf->Cell(90, 6, $title1);
    $pdf->SetXY(105, $y);
    $pdf->Cell(90, 6, $title2);
    $y += 6;

    $pdf->SetFont('Arial','',8);
    $pdf->SetXY(12, $y);
    $pdf->Cell(10, 5, "[PDF]");
    $pdf->SetXY(107, $y);
    $pdf->Cell(10, 5, "[PDF]");
    $y += 12;

    $url1 = $upload_url . $file1;
    $url2 = $upload_url . $file2;

    $pdf->SetTextColor(0, 0, 255);
    $pdf->SetFont('Arial','U',8);

    $pdf->SetXY(12, $y);
    $pdf->Cell(85, 5, basename($file1), 0, 0, 'L', false, $url1);

    $pdf->SetXY(107, $y);
    $pdf->Cell(85, 5, basename($file2), 0, 0, 'L', false, $url2);

    $pdf->SetTextColor(0, 0, 0);
    $y += 12;
}
docPair($pdf, "10th Marks Memo", $row['ssc'], "Intermediate / 12th Marks Memo", $row['inter'], $y, $upload_dir);
docPair($pdf, "Passport/VISA of NRI", $row['passport'], "Address Proof", $row['address_proof'], $y, $upload_dir);
// ==================== PAGE 2 ====================
$pdf->AddPage();

// ================= PREFERENCES =================
$y = 10;
for($i = 1; $i <= 5; $i++){
    $pref = $row["pref".$i];
    $pdf->SetFont('Arial','B',9);
    $pdf->SetXY(10, $y);
    $pdf->Cell(50, 10, "Preference $i");

    $pdf->SetFillColor(240, 240, 245);
    $pdf->SetFont('Arial','',9);
    $pdf->SetXY(60, $y);
    $pdf->Cell(140, 10, "  $pref", 0, 1, 'L', true);
    $y += 13;
}

// ================= DECLARATION =================
$y += 8;
$pdf->SetFont('Arial','BU',11);
$pdf->SetXY(10, $y);
$pdf->Cell(0, 6, "DECLARATION", 0, 1, 'C');
$y += 10;

$pdf->SetFont('Arial','',9);
$pdf->SetXY(15, $y);
$pdf->MultiCell(180, 5,
    "We hereby declare that all the information furnished above is true to the best of our knowledge. We are aware and give you an undertaking that our application form can summarily be rejected if any information provided is wrong."
);
$y += 18;

// ================= SIGNATURES =================
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(10, $y);
$pdf->MultiCell(60, 5, "Scanned Signature of\nApplicant");

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(140, $y);
$pdf->MultiCell(60, 5, "Scanned Signature of\nParent", 0, 'L');
$y += 12;

$studSign = __DIR__ . "/uploads/" . basename($row['stud_sign']);
$parentSign = __DIR__ . "/uploads/" . basename($row['parent_sign']);

if(!empty($row['stud_sign']) && file_exists($studSign)){
    $pdf->Image($studSign, 10, $y, 55, 25);
}

if(!empty($row['parent_sign']) && file_exists($parentSign)){
    $pdf->Image($parentSign, 140, $y, 55, 25);
}

// ================= SIGNATURE LABELS =================
$y += 27;
$pdf->SetFont('Arial','',9);
$pdf->SetXY(10, $y);
$pdf->Cell(55, 5, "Applicant's Signature", 0, 0, 'C');
$pdf->SetXY(140, $y);
$pdf->Cell(55, 5, "Parent's Signature", 0, 0, 'C');
$y += 12;

// ================= TRANSACTION =================
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(10, $y);
$pdf->Cell(50, 6, "Transaction ID", 0, 1);

$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Cell(0, 6, $row['txnid'], 0, 1);
$y += 7;
$pdf->SetDrawColor(180, 180, 180);
$pdf->Line(10, $y, 80, $y);
$y += 10;

$pdf->SetFillColor(240, 240, 245);
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(10, $y);
$pdf->Cell(130, 7, "  Description", 1, 0, 'L', true);
$pdf->Cell(60, 7, "Amount", 1, 1, 'R', true);
$pdf->SetFont('Arial','',9);
$pdf->SetXY(10, $y+7);
$pdf->Cell(130, 7, "  ".$row['payment_amount']." INR x 1", 1, 0, 'L');
$pdf->Cell(60, 7, $row['payment_amount']." INR", 1, 1, 'R');
$y += 20;

$pdf->SetFont('Arial','B',12);
$pdf->SetXY(100, $y);
$pdf->Cell(40, 8, "Total", 0, 0, 'R');
$pdf->Cell(60, 8, $row['payment_amount']." INR", 0, 0, 'R');

// ================= OUTPUT =================

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'.$row['name'].'.pdf"');
header('X-Content-Type-Options: nosniff');
$pdf->Output("D", $row['name'].".pdf");
?>
