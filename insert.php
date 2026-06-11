<?php
$host = "localhost";
$user = "gnitsc79_gnitsc79";
$pass = "@Gnits@123456@";
$dbname = "gnitsc79_gnits_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// ==========================
// UPLOAD SETTINGS
// ==========================
$upload_dir = __DIR__ . "/uploads/";
$upload_url = "http://localhost/Mini_Project/uploads/";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

// ==========================
// UPLOAD FUNCTION
// ==========================
function uploadFile($file, $upload_dir, $type="doc"){

    if(!isset($file) || $file['error']!=0) return null;

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $img_ext = ['jpg','jpeg','png'];
    $doc_ext = ['pdf'];

    if($type=="image" && !in_array($ext,$img_ext)) die("Invalid image");
    if($type=="doc" && !in_array($ext,$doc_ext)) die("Invalid PDF");

    $max = ($type=="image") ? 200*1024 : 300*1024;
    if($file['size'] > $max) die("File too large");

    $name = uniqid()."_".time().".".$ext;
    $path = $upload_dir.$name;

   if ($type == "image") {

    // Check if GD library is available
    if (function_exists('imagecreatetruecolor')) {

        $info = getimagesize($file['tmp_name']);

        if ($info === false) {
            die("Invalid image file");
        }

        $w = $info[0];
        $h = $info[1];

        // Avoid division by zero
        if ($w == 0 || $h == 0) {
            die("Invalid image dimensions");
        }

        $nw = 300;
        $nh = ($h / $w) * $nw;

        $tmp = imagecreatetruecolor($nw, $nh);

        // Handle image types safely
        if ($ext == "png") {
            $src = imagecreatefrompng($file['tmp_name']);
        } elseif ($ext == "jpg" || $ext == "jpeg") {
            $src = imagecreatefromjpeg($file['tmp_name']);
        } else {
            die("Unsupported image type");
        }

        // Resize
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        // Save compressed image
        imagejpeg($tmp, $path, 70);

        // Free memory
        imagedestroy($tmp);
        imagedestroy($src);

    } else {
        // ❗ Fallback if GD is NOT enabled
        move_uploaded_file($file['tmp_name'], $path);
    }

} else {
    // PDF or other files
    move_uploaded_file($file['tmp_name'], $path);
}   return $name;
}

// ==========================
// FORM SUBMIT
// ==========================
if($_SERVER["REQUEST_METHOD"]=="POST"){

// ==========================
// GET DATA
// ==========================
$name=$_POST['name'];
$dob=$_POST['dob'];
$father=$_POST['father'];
$mother=$_POST['mother'];

$gender = $_POST['gender'];
$community = $_POST['community'];

$address1=$_POST['address1'];
$address2=$_POST['address2'];
$city=$_POST['city'];
$state=$_POST['state'];
$zip=$_POST['zip'];
$country=$_POST['country'];

$email=$_POST['email'];
$confirm_email=isset($_POST['confirm_email'])?1:0;
$aadhar=$_POST['aadhar'];
// Combine area code + mobile
$mobile = $_POST['area_code'] . "-" . $_POST['mobile'];

$board=$_POST['board'];
$passing_year=$_POST['passing_year'];
$total_marks=$_POST['total_marks'];
$group_marks=$_POST['group_marks'];

$jee_rank=$_POST['jee_rank'];
$jee_percentile=$_POST['jee_percentile'];
$eamcet_rank=$_POST['eamcet_rank'];

$pref1=$_POST['pref1'];
$pref2=$_POST['pref2'];
$pref3=$_POST['pref3'];
$pref4=$_POST['pref4'];
$pref5=$_POST['pref5'];

// ==========================
// NEW FIELDS (ADD ONLY)
// ==========================


$intermediate_hall_ticket = $_POST['intermediate_hall_ticket'];
$inter_percentage = $_POST['inter_percentage'];
$inter_group_percentage = $_POST['inter_group_percentage'];

$jee_hall_ticket = $_POST['jee_hall_ticket'];
$eamcet_hall_ticket = $_POST['eamcet_hall_ticket'];

// ==========================
// VALIDATE PREFERENCES
// ==========================
$prefs=[$pref1,$pref2,$pref3,$pref4,$pref5];

if(in_array("",$prefs)) die("Select all preferences");
if(count($prefs)!=count(array_unique($prefs))) die("Duplicate prefs");

// ==========================
// NEW VALIDATIONS
// ==========================

// Gender check
if (!in_array($gender, ['F','M','O'])) {
    die("Invalid Gender");
}

// Community check
if (!in_array($community, ['SC','ST','OBC','EWS','GENERAL'])) {
    die("Invalid Community");
}

// Percentage validation
if ($inter_percentage < 0 || $inter_percentage > 100) {
    die("Invalid Intermediate Percentage");
}

if ($inter_group_percentage < 0 || $inter_group_percentage > 100) {
    die("Invalid Group Percentage");
}

// ==========================
// UPLOAD FILES
// ==========================
$photo=uploadFile($_FILES['photo'],$upload_dir,"image");
$stud_sign=uploadFile($_FILES['stud_sign'],$upload_dir,"image");
$parent_sign=uploadFile($_FILES['parent_sign'],$upload_dir,"image");

$tenth=uploadFile($_FILES['tenth'],$upload_dir,"doc");
$inter=uploadFile($_FILES['inter'],$upload_dir,"doc");
$jee_card=uploadFile($_FILES['jee_card'],$upload_dir,"doc");
$eamcet_card=uploadFile($_FILES['eamcet_card'],$upload_dir,"doc");

// FULL PATHS
$photo_path=$upload_dir.$photo;
$stud_sign_path=$upload_dir.$stud_sign;
$parent_sign_path=$upload_dir.$parent_sign;

$tenth_path=$upload_dir.$tenth;
$inter_path=$upload_dir.$inter;
$jee_path=$upload_dir.$jee_card;
$eamcet_path=$upload_dir.$eamcet_card;

// URLS
$ssc_url=$upload_url.$tenth;
$inter_url=$upload_url.$inter;
$jee_url=$upload_url.$jee_card;
$eamcet_url=$upload_url.$eamcet_card;

// ==========================
// DUPLICATE CHECKS (NEW)
// ==========================

// JEE Hall Ticket
$checkJee = $conn->prepare("SELECT id FROM jee WHERE jee_hall_ticket=?");
$checkJee->bind_param("s", $jee_hall_ticket);
$checkJee->execute();
if ($checkJee->get_result()->num_rows > 0) {
    die("JEE Hall Ticket already exists!");
}

// INTER Hall Ticket
$checkInter = $conn->prepare("SELECT id FROM jee WHERE intermediate_hall_ticket=?");
$checkInter->bind_param("s", $intermediate_hall_ticket);
$checkInter->execute();
if ($checkInter->get_result()->num_rows > 0) {
    die("Intermediate Hall Ticket already exists!");
}

// EAMCET Hall Ticket
if (!empty($eamcet_hall_ticket)) {
    $checkEamcet = $conn->prepare("SELECT id FROM jee WHERE eamcet_hall_ticket=?");
    $checkEamcet->bind_param("s", $eamcet_hall_ticket);
    $checkEamcet->execute();
    if ($checkEamcet->get_result()->num_rows > 0) {
        die("EAMCET Hall Ticket already exists!");
    }
}
if (!preg_match("/^[A-Z ]{3,100}$/", $name)) {
    die("Name must be in capital letters only");
}
if (!preg_match("/^\+91-[6-9][0-9]{9}$/", $mobile)) {
    die("Invalid Mobile Number format");
}

// ==========================
// INSERT DB
// ==========================
 $stmt = $conn->prepare("INSERT INTO jee (
reference_no, name, gender, dob, father, mother, community,
address1, address2, city, state, zip, country,
email, confirm_email, aadhar, mobile,
board, passing_year, intermediate_hall_ticket, inter_percentage, inter_group_percentage, total_marks, group_marks,
jee_hall_ticket, jee_rank, jee_percentile, eamcet_hall_ticket, eamcet_rank,
pref1, pref2, pref3, pref4, pref5,
photo, tenth, inter, jee_card, eamcet_card, stud_sign, parent_sign
)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
  
  $stmt->bind_param(
"ssssssssssssissssssddiiisidsissssssssssss",
$reference_no, $name, $gender, $dob, $father, $mother, $community,
$address1, $address2, $city, $state, $zip, $country,
$email, $confirm_email, $aadhar, $mobile,
$board, $passing_year, $intermediate_hall_ticket, $inter_percentage, $inter_group_percentage, $total_marks, $group_marks,
$jee_hall_ticket, $jee_rank, $jee_percentile, $eamcet_hall_ticket, $eamcet_rank,
$pref1, $pref2, $pref3, $pref4, $pref5,
$photo, $tenth, $inter, $jee_card, $eamcet_card, $stud_sign, $parent_sign
);

if($stmt->execute()){

// ==========================
// PDF
// ==========================
 
require('lib/fpdf-master/fpdf.php');

$pdf = new FPDF();
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();

// ================= COLLEGE HEADER =================
// ================= HEADER AS IMAGE =================
// Place your college header image at: images/gnits_header.png
// This replaces all the text-based header lines
if(file_exists('pics/collage_header.png')){
    $pdf->Image('pics/collage_header.png', 10, 5, 190, 30); // full width header image
}

// ================= JEE ID & DATE =================
$id = $conn->insert_id;
$reference_no = "JEE" . $id ;
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(10, 38);
$pdf->Cell(100, 5, "JEE ID: " . $reference_no, 0, 0);
$pdf->Cell(0, 5, date("F d, Y"), 0, 1, 'R');
$update = $conn->prepare("UPDATE jee SET reference_no=? WHERE id=?");
$update->bind_param("si", $reference_no, $id);
$update->execute();

// ================= TITLE =================
$pdf->SetFont('Arial','B',12);
$pdf->SetXY(15, 45);
$pdf->Cell(0, 5, "Application for Admission into first year B.Tech course under Category-B (JEE Mains)", 0, 1, 'C');
$pdf->Cell(0, 5, "for the A.Y 2025-26", 0, 1, 'C');

// ================= PHOTO (right side, with space reserved) =================
$photoX = 155;
$photoY = 55;
$photoW = 35;
$photoH = 42;

if(file_exists($photo_path)){
    $pdf->Image($photo_path, $photoX, $photoY, $photoW, $photoH);
}
$pdf->SetFont('Arial','B',7);
$pdf->SetXY($photoX - 5, $photoY + $photoH + 1);
$pdf->Cell($photoW + 10, 4, "Upload Latest Passport", 0, 1, 'C');
$pdf->SetXY($photoX - 5, $photoY + $photoH + 5);
$pdf->Cell($photoW + 10, 4, "Size Colour Photograph", 0, 1, 'C');

// ================= DETAILS (limit width so text doesn't hit photo) =================
$y = 58;
$maxValueWidth = 80; // keep text within left 140mm so it doesn't overlap photo

function labelValueRow($pdf, $label, $value, &$y, $labelWidth=60){
    $pdf->SetFont('Arial','B',9);
    $pdf->SetXY(10, $y);
    $pdf->Cell($labelWidth, 7, $label);
    $pdf->SetFont('Arial','',9);
    $pdf->SetXY(10 + $labelWidth, $y);
    $pdf->Cell(80, 7, $value);
    $y += 8;
}

labelValueRow($pdf, "Name of the Applicant", $name, $y);
labelValueRow($pdf, "Date of Birth", $dob, $y);
labelValueRow($pdf, "Father's Name", $father, $y);
labelValueRow($pdf, "Mother's Name", $mother, $y);
labelValueRow($pdf, "Mobile No.", $mobile, $y);
labelValueRow($pdf, "Aadhar (UID) No.", $aadhar, $y);

// ================= ADDRESS =================
// ================= ADDRESS =================
$pdf->SetFont('Arial','B',10);

// Left column (label)
$pdf->SetXY(10, $y);
$pdf->MultiCell(60, 5, "Address for Communication (Block Letters)");

// Save Y position after label
$y_label_end = $pdf->GetY();

// Right column (address)
$pdf->SetFont('Arial','',10);
$pdf->SetXY(70, $y); // start same top line as label

$pdf->MultiCell(120, 5,
"$address1
$city, $state, $zip
$country"
);

// Get max Y to continue properly
$y = max($y_label_end, $pdf->GetY());
$y += 2; // small spacing after block

// ================= EMAIL =================
labelValueRow($pdf, "Email Address", $email, $y);

// ================= LINE SEPARATOR =================
$pdf->Line(10, $y, 200, $y);
$y += 3;

// ================= EDUCATION =================
labelValueRow($pdf, "Name of the Board", $board, $y);
labelValueRow($pdf, "Month & Year of passing", $passing_year, $y);

// Total Marks / Group Marks on same line
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(10, $y);
$pdf->Cell(30, 7, "Total Marks");
$pdf->SetFont('Arial','',9);
$pdf->SetXY(40, $y);
$pdf->Cell(30, 7, $total_marks);

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(100, $y);
$pdf->Cell(40, 7, "Total Group Marks");
$pdf->SetFont('Arial','',9);
$pdf->SetXY(145, $y);
$pdf->Cell(30, 7, $group_marks);
$y += 8;

// JEE Rank / Percentile on same line
$pdf->SetFont('Arial','B',9);
$pdf->SetXY(10, $y);
$pdf->Cell(65, 7, "All India Rank in JEE (Main) - 2025");
$pdf->SetFont('Arial','',9);
$pdf->SetXY(75, $y);
$pdf->Cell(25, 7, $jee_rank);

$pdf->SetFont('Arial','B',9);
$pdf->SetXY(100, $y);
$pdf->Cell(55, 7, "Percentile in JEE (Main) - 2025");
$pdf->SetFont('Arial','',9);
$pdf->SetXY(165, $y);
$pdf->Cell(30, 7, $jee_percentile);
$y += 8;

labelValueRow($pdf, "Rank in TGEAPCET - 2025", $eamcet_rank, $y);

// ================= LINE SEPARATOR =================
$pdf->Line(10, $y, 200, $y);
$y += 4;

// ================= DOCUMENTS (side by side) =================
function docPair($pdf, $title1, $url1, $title2, $url2, &$y){
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

    $pdf->SetTextColor(0, 0, 255);
    $pdf->SetFont('Arial','U',8);
    $pdf->SetXY(12, $y);
    $pdf->Cell(85, 5, basename($url1), 0, 0, 'L', false, $url1);
    $pdf->SetXY(107, $y);
    $pdf->Cell(85, 5, basename($url2), 0, 0, 'L', false, $url2);
    $pdf->SetTextColor(0, 0, 0);
    $y += 12;
}

docPair($pdf, "10th Marks Memo", $ssc_url, "Intermediate / 12th Marks Memo", $inter_url, $y);
docPair($pdf, "JEE Mains 2025 Rank Card", $jee_url, "TGEAPCET 2025 Rank Card (optional)", $eamcet_url, $y);

// ==================== PAGE 2 ====================
$pdf->AddPage();

// ================= PREFERENCES =================
$y = 10;
for($i = 1; $i <= 5; $i++){
    $pref = ${"pref".$i};
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

if(file_exists($stud_sign_path)){
    $pdf->Image($stud_sign_path, 10, $y, 55, 25);
}
if(file_exists($parent_sign_path)){
    $pdf->Image($parent_sign_path, 140, $y, 55, 25);
}

// ================= SIGNATURE LABELS =================
$y += 27;

$pdf->SetFont('Arial','',9);
$pdf->SetXY(10, $y);
$pdf->Cell(55, 5, "Applicant's Signature", 0, 0, 'C');

$pdf->SetXY(140, $y);
$pdf->Cell(55, 5, "Parent's Signature", 0, 0, 'C');

// 👉 MOVE DOWN AFTER SIGNATURES
$y += 12;   // increase spacing here (adjust if needed)

// ================= TRANSACTION =================
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(10, $y);
$pdf->Cell(50, 6, "Transaction ID", 0, 1);

// value
$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Cell(0, 6, $transaction_id, 0, 1);
$y += 7;
$pdf->SetDrawColor(180, 180, 180);
$pdf->Line(10, $y, 80, $y);

// Uncomment when you have transaction data:
// $y += 2;
// $pdf->SetFont('Arial','',9);
// $pdf->SetXY(10, $y);
// $pdf->Cell(70, 6, $transaction_id, 0, 0);
$y += 10;
 $pdf->SetFillColor(240, 240, 245);
 $pdf->SetFont('Arial','B',9);
 $pdf->SetXY(10, $y);
 $pdf->Cell(130, 7, "  Description", 1, 0, 'L', true);
 $pdf->Cell(60, 7, "Amount", 1, 1, 'R', true);
 $pdf->SetFont('Arial','',9);
 $pdf->SetXY(10, $y+7);
 //$pdf->Cell(130, 7, "  ".$amount." INR x 1", 1, 0, 'L');
 //$pdf->Cell(60, 7, $amount." INR", 1, 1, 'R');
 $y += 20;
 $pdf->SetFont('Arial','B',12);
 $pdf->SetXY(100, $y);
 $pdf->Cell(40, 8, "Total", 0, 0, 'R');
// $pdf->Cell(60, 8, $amount." INR", 0, 0, 'R');


// ================= SAVE =================
$pdf_file = $upload_dir . $name . ".pdf";
$pdf->Output($pdf_file, "F");


// ==========================
// EMAIL
// ==========================
require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';
require 'lib/PHPMailer/src/Exception.php';


// STUDENT
$mail=new PHPMailer(true);
$mail->isSMTP();
$mail->Host='smtp.gmail.com';
$mail->SMTPAuth=true;
$mail->Username='shanvitharaj06@gmail.com';
$mail->Password='imjz bewy fart cpnd';

$mail->SMTPSecure='tls';
$mail->Port=587;

$mail->setFrom('your@gmail.com');
$mail->addAddress($email);
$mail->Subject='Application Submitted';
$mail->Body="Ref No: $reference_no";
$mail->addAttachment($pdf_file);
$mail->send();

// ADMIN
$admin=new PHPMailer(true);
$admin->isSMTP();
$admin->Host='smtp.gmail.com';
$admin->SMTPAuth=true;
$admin->Username='shanvitharaj06@gmail.com';
$admin->Password='imjz bewy fart cpnd';

$admin->SMTPSecure='tls';
$admin->Port=587;

$admin->setFrom('your@gmail.com');
$admin->addAddress('shanvitharaj06@gmail.com');

$admin->isHTML(true);
$admin->Subject='New JEE Application';

$admin->Body = "

<h2 style='font-family:Arial;'>$name - $reference_no</h2>

<table style='border-collapse:collapse;width:100%;font-family:Arial,sans-serif;font-size:14px;border:1px solid #ddd;'>

<tr>
<th colspan='2' style='background:#4CAF50;color:white;padding:10px;text-align:left;'>Personal Details</th>
</tr>

<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Name</td><td style='padding:8px;border:1px solid #ddd;'>$name</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Reference No</td><td style='padding:8px;border:1px solid #ddd;'>$reference_no</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Gender</td><td style='padding:8px;border:1px solid #ddd;'>$gender</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Community</td><td style='padding:8px;border:1px solid #ddd;'>$community</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Date of Birth</td><td style='padding:8px;border:1px solid #ddd;'>$dob</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Father Name</td><td style='padding:8px;border:1px solid #ddd;'>$father</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Mother Name</td><td style='padding:8px;border:1px solid #ddd;'>$mother</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Mobile</td><td style='padding:8px;border:1px solid #ddd;'>$mobile</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Email</td><td style='padding:8px;border:1px solid #ddd;'>$email</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Aadhar</td><td style='padding:8px;border:1px solid #ddd;'>$aadhar</td></tr>

<tr>
<th colspan='2' style='background:#4CAF50;color:white;padding:10px;text-align:left;'>Address</th>
</tr>

<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Address</td><td style='padding:8px;border:1px solid #ddd;'>$address1, $address2</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>City</td><td style='padding:8px;border:1px solid #ddd;'>$city</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>State</td><td style='padding:8px;border:1px solid #ddd;'>$state</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>ZIP</td><td style='padding:8px;border:1px solid #ddd;'>$zip</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Country</td><td style='padding:8px;border:1px solid #ddd;'>$country</td></tr>

<tr>
<th colspan='2' style='background:#4CAF50;color:white;padding:10px;text-align:left;'>Academic Details</th>
</tr>

<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Board</td><td style='padding:8px;border:1px solid #ddd;'>$board</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Passing Year</td><td style='padding:8px;border:1px solid #ddd;'>$passing_year</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Intermediate Hall Ticket</td><td style='padding:8px;border:1px solid #ddd;'>$intermediate_hall_ticket</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Intermediate Percentage</td><td style='padding:8px;border:1px solid #ddd;'>$inter_percentage</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Inter Group Percentage</td><td style='padding:8px;border:1px solid #ddd;'>$inter_group_percentage</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Total Marks</td><td style='padding:8px;border:1px solid #ddd;'>$total_marks</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Group Marks</td><td style='padding:8px;border:1px solid #ddd;'>$group_marks</td></tr>

<tr>
<th colspan='2' style='background:#4CAF50;color:white;padding:10px;text-align:left;'>Entrance Details</th>
</tr>

<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>JEE Hall Ticket</td><td style='padding:8px;border:1px solid #ddd;'>$jee_hall_ticket</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>JEE Rank</td><td style='padding:8px;border:1px solid #ddd;'>$jee_rank</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>JEE Percentile</td><td style='padding:8px;border:1px solid #ddd;'>$jee_percentile</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>EAMCET Hall Ticket</td><td style='padding:8px;border:1px solid #ddd;'>$eamcet_hall_ticket</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>EAMCET Rank</td><td style='padding:8px;border:1px solid #ddd;'>$eamcet_rank</td></tr>

<tr>
<th colspan='2' style='background:#4CAF50;color:white;padding:10px;text-align:left;'>Course Preferences</th>
</tr>

<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Preference 1</td><td style='padding:8px;border:1px solid #ddd;'>$pref1</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Preference 2</td><td style='padding:8px;border:1px solid #ddd;'>$pref2</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Preference 3</td><td style='padding:8px;border:1px solid #ddd;'>$pref3</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Preference 4</td><td style='padding:8px;border:1px solid #ddd;'>$pref4</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Preference 5</td><td style='padding:8px;border:1px solid #ddd;'>$pref5</td></tr>

</table>

<br><h3 style='font-family:Arial;'>Uploaded Documents</h3>

<table style='border-collapse:collapse;width:100%;font-family:Arial,sans-serif;font-size:14px;border:1px solid #ddd;'>
<tr style='background:#4CAF50;color:white;'>
<th style='padding:10px;border:1px solid #ddd;'>Document</th>
<th style='padding:10px;border:1px solid #ddd;'>View</th>
</tr>

<tr><td style='padding:8px;border:1px solid #ddd;'>10th Marks Memo</td><td style='padding:8px;border:1px solid #ddd;'><a href='$ssc_url' style='color:white;background:#007BFF;padding:5px 10px;text-decoration:none;border-radius:4px;'>Open</a></td></tr>

<tr><td style='padding:8px;border:1px solid #ddd;'>Inter Marks Memo</td><td style='padding:8px;border:1px solid #ddd;'><a href='$inter_url' style='color:white;background:#007BFF;padding:5px 10px;text-decoration:none;border-radius:4px;'>Open</a></td></tr>

<tr><td style='padding:8px;border:1px solid #ddd;'>JEE Rank Card</td><td style='padding:8px;border:1px solid #ddd;'><a href='$jee_url' style='color:white;background:#007BFF;padding:5px 10px;text-decoration:none;border-radius:4px;'>Open</a></td></tr>

<tr><td style='padding:8px;border:1px solid #ddd;'>EAMCET Rank Card</td><td style='padding:8px;border:1px solid #ddd;'><a href='$eamcet_url' style='color:white;background:#007BFF;padding:5px 10px;text-decoration:none;border-radius:4px;'>Open</a></td></tr>

</table>
";
// Documents
// PDF
$admin->addAttachment($pdf_file);

// Documents
$admin->addAttachment($tenth_path);
$admin->addAttachment($inter_path);
$admin->addAttachment($jee_path);
$admin->addAttachment($eamcet_path);

// Photo
if(file_exists($photo_path)){
    $admin->addAttachment($photo_path, "Photo.jpg");
}

/// Parent Signature
if(file_exists($parent_sign_path)){
    $admin->addAttachment($parent_sign_path, "Parent_Signature.png");
}

// Student Signature
if(file_exists($stud_sign_path)){
    $admin->addAttachment($stud_sign_path, "Student_Signature.png");
}
$admin->send();

header("Location: success.php?ref=".$reference_no);
exit();
}

}
?>
 