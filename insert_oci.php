<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "gnits_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$upload_dir = __DIR__ . "/uploads/";
$upload_url = "http://localhost/Mini_Project/uploads/";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $reference_no = "REF" . time();

    // ================= FORM DATA =================
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $father = $_POST['father'];
    $mother = $_POST['mother'];
    $community = $_POST['community'];

    $mobile = $_POST['area_code'] . "-" . $_POST['mobile'];
    $aadhar = $_POST['aadhar'];
    $email = $_POST['email'];
    $confirm_email = isset($_POST['confirm_email']) ? 1 : 0;

    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $country = $_POST['country'];

    $board = $_POST['board'];
    $passing_year = $_POST['passing_year'];
    $total_marks = $_POST['total_marks'];
    $group_marks = $_POST['group_marks'];
    $intermediate_hall_ticket = $_POST['intermediate_hall_ticket'];
    $inter_percentage = $_POST['inter_percentage'];
    $inter_group_percentage = $_POST['inter_group_percentage'];


    $pref1 = $_POST['pref1'];
    $pref2 = $_POST['pref2'];
    $pref3 = $_POST['pref3'];
    $pref4 = $_POST['pref4'];
    $pref5 = $_POST['pref5'];

    $place_country = $_POST['place_country'];

    // ================= VALIDATIONS =================
    if (!preg_match("/^\+91-[6-9][0-9]{9}$/", $mobile)) {
        die("Invalid Mobile Number");
    }

    if (!preg_match("/^[A-Z ]{3,100}$/", $name)) {
        die("Name must be in CAPS");
    }

    $prefs = [$pref1, $pref2, $pref3, $pref4, $pref5];

    if(in_array("", $prefs)){
        die("Select all 5 preferences");
    }

    if(count($prefs) != count(array_unique($prefs))){
        die("Duplicate preferences not allowed");
    }

    // ================= FILE UPLOAD =================
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    function uploadFile($file, $dir) {
        $filename = time() . "_" . basename($file["name"]);
        $target = $dir . $filename;
        move_uploaded_file($file["tmp_name"], $target);
        return $target;
    }
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

    $photo = uploadFile($_FILES['photo'], $upload_dir);
    $ssc = uploadFile($_FILES['ssc'], $upload_dir);
    $inter = uploadFile($_FILES['inter'], $upload_dir);
    $passport = uploadFile($_FILES['passport'], $upload_dir);
    $address_proof = uploadFile($_FILES['address_proof'], $upload_dir);
    $stud_sign = uploadFile($_FILES['stud_sign'], $upload_dir);
    $parent_sign = uploadFile($_FILES['parent_sign'], $upload_dir);
    //urls

    $ssc_url = $upload_url . basename($ssc);
$inter_url = $upload_url . basename($inter);
$passport_url = $upload_url . basename($passport);
$address_proof_url = $upload_url . basename($address_proof);

    // ================= DATABASE =================
    $sql = "INSERT INTO oci (
        reference_no,name,gender,dob,father,mother,community,
        mobile,aadhar,email,confirm_email,
        address1,address2,city,state,zip,country,
        board,passing_year,total_marks,group_marks,intermediate_hall_ticket, inter_percentage, inter_group_percentage,
        pref1,pref2,pref3,pref4,pref5,place_country,
        photo,ssc,inter,passport,address_proof,stud_sign,parent_sign
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);

    $types = str_repeat("s", 37);

    $stmt->bind_param(
        $types,
        $reference_no,$name,$gender,$dob,$father,$mother,$community,
        $mobile,$aadhar,$email,$confirm_email,
        $address1,$address2,$city,$state,$zip,$country,
        $board,$passing_year,$total_marks,$group_marks,$intermediate_hall_ticket, $inter_percentage, $inter_group_percentage,
        $pref1,$pref2,$pref3,$pref4,$pref5,$place_country,
        $photo,$ssc,$inter,$passport,$address_proof,$stud_sign,$parent_sign
    );

    if ($stmt->execute()) {

        // ================= PDF =================
        require('lib/fpdf-master/fpdf.php');

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

/* ================= HEADER ================= */
$headerImg = __DIR__ . '/pics/collage_header.png';
$pdf->Image($headerImg, 10, 5, 190);
$pdf->Ln(35);

/* ================= TITLE ================= */
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(0,7,'Application for Admission into First Year B.Tech course under Foreign Nationals / Overseas
Citizen of India / Children of Indian Workers in Gulf Countries for the A.Y 2025-26',0,'C');
$pdf->Ln(3);

/* ================= REF NO ================= */
$id = $conn->insert_id;
$reference_no = "OCI".$id;

$pdf->SetFont('Arial','B',10);
$pdf->Cell(100,6,"OCI ID: $reference_no",0,0);
$pdf->Cell(0,6,date("F d, Y"),0,1,'R');

$update = $conn->prepare("UPDATE oci SET reference_no=? WHERE id=?");
$update->bind_param("si",$reference_no,$id);
$update->execute();

$pdf->Ln(3);

/* ================= PHOTO ================= */
$detailsY = $pdf->GetY();

$pdf->SetXY(150, $detailsY);
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(45,5,'Passport Size Photo',0,'C');

if(!empty($photo)){
    $pdf->Image($photo,155,$detailsY+10,35,40);
}
$pdf->Rect(153,$detailsY+10,40,42);

/* ================= FUNCTION ================= */
$labelW=55; $valueW=80; $rowH=7;

function labelValue($pdf,$label,$value,$lw,$vw,$rh){
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($lw,$rh,$label,0,0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell($vw,$rh,$value,0,1);
    $pdf->SetX(10);
}

/* ================= PERSONAL ================= */
$pdf->SetXY(10,$detailsY);

labelValue($pdf,'Name',$name,$labelW,$valueW,$rowH);
labelValue($pdf,'Gender',$gender,$labelW,$valueW,$rowH);
labelValue($pdf,'DOB',$dob,$labelW,$valueW,$rowH);
labelValue($pdf,"Father's Name",$father,$labelW,$valueW,$rowH);
labelValue($pdf,"Mother's Name",$mother,$labelW,$valueW,$rowH);

$pdf->Ln(8);

/* ================= CONTACT ================= */
labelValue($pdf,'Mobile',$mobile,$labelW,$valueW,$rowH);
labelValue($pdf,'Email',$email,$labelW,$valueW,$rowH);
labelValue($pdf,'Aadhar',$aadhar,$labelW,$valueW,$rowH);

$pdf->Ln(5);

/* ================= ADDRESS ================= */
$pdf->SetFont('Arial','B',10);
$pdf->Cell($labelW,$rowH,'Address',0,0);
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(130,5,$address1.', '.$address2."\n".$city.', '.$state.' '.$zip.', '.$country);

$pdf->Ln(3);

/* ================= LINE ================= */
$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
$pdf->Ln(3);

/* ================= ACADEMIC ================= */
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,7,'Academic Details',0,1);

$pdf->Ln(2);

labelValue($pdf,'Board',$board,$labelW,$valueW,$rowH);
labelValue($pdf,'Passing Year',$passing_year,$labelW,$valueW,$rowH);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,$rowH,'Total Marks',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,$rowH,$total_marks,0,0);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,$rowH,'Group Marks',0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,$rowH,$group_marks,0,1);

$pdf->Ln(3);

/* ================= COUNTRY ================= */
labelValue($pdf,'Place/Country',$place_country,$labelW,$valueW,$rowH);

/* ================= PAGE 2 ================= */
$pdf->AddPage();

/* ================= DOCUMENT LINKS ================= */

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
    $y += 6;

    $pdf->SetTextColor(0, 0, 255);
    $pdf->SetFont('Arial','U',8);
    $pdf->SetXY(12, $y);
    $pdf->Cell(85, 5, basename($url1), 0, 0, 'L', false, $url1);
    $pdf->SetXY(107, $y);
    $pdf->Cell(85, 5, basename($url2), 0, 0, 'L', false, $url2);
    $pdf->SetTextColor(0, 0, 0);
    $y += 6;
}

docpair($pdf,"10th Memo",$upload_url.basename($ssc),"Inter Memo",$upload_url.basename($inter),$y);
docPair($pdf,"Passport",$upload_url.basename($passport),"Address Proof",$upload_url.basename($address_proof),$y);

$pdf->Ln(5);

/* ================= PREFERENCES ================= */
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(51, 51, 153);
$pdf->Ln(5);
$pdf->Cell(0, 8, 'Select the 5 courses in the order of Preference', 0, 1, 'L');
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 10);
$prefLabelW = 40;
$prefValueW = 150;

function prefRow($pdf, $label, $value, $lw, $vw) {
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell($lw, 8, $label, 0, 0, 'L');
    $pdf->Cell($vw, 8, $value, 0, 1, 'L');
    $pdf->Ln(1);
}

prefRow($pdf, 'Preference 1', $pref1, $prefLabelW, $prefValueW);
prefRow($pdf, 'Preference 2', $pref2, $prefLabelW, $prefValueW);
prefRow($pdf, 'Preference 3', $pref3, $prefLabelW, $prefValueW);
prefRow($pdf, 'Preference 4', $pref4, $prefLabelW, $prefValueW);
if (!empty($pref5)) {
    prefRow($pdf, 'Preference 5', $pref5, $prefLabelW, $prefValueW);
}

// --- Declaration ---
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'DECLARATION', 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 5, 'We hereby declare that all the information furnished above is true to the best of our knowledge. We are aware and give you an undertaking that our application form can summarily be rejected if any information provided is wrong.', 0, 'C');

// --- Transaction ---
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'Transaction ID', 0, 1, 'L');
//$pdf->Line(10, $pdf->GetY(), 75, $pdf->GetY());
//$pdf->SetFont('Arial', 'B', 10);
//$pdf->Cell(0, 7, $transaction_id, 0, 1, 'L'); // e.g. 6295972650329081594

// --- Payment Table ---
$pdf->Ln(2);
$pdf->SetFillColor(230, 230, 240);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(140, 8, 'Description', 1, 0, 'L', true);
$pdf->Cell(50, 8, 'Amount', 1, 1, 'R', true);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(140, 8, '2,100.00 INR x 1', 1, 0, 'L');
$pdf->Cell(50, 8, '2,100.00 INR', 1, 1, 'R');

$pdf->Line(95, $pdf->GetY() + 2, 200, $pdf->GetY() + 2);
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(140, 8, '', 0, 0);
$pdf->Cell(10, 8, 'Total', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 8, '2,100.00 INR', 0, 1, 'R');

// --- Signature Section ---
$pdf->Ln(6);

// Column widths
$colW = 95;
$leftX = 10;
$rightX = 10 + $colW;

// --- Heading text ---
$pdf->SetFont('Arial', 'B', 9);

$pdf->Cell($colW, 5, 'Scanned Signature', 0, 0, 'C');
$pdf->Cell($colW, 5, 'Scanned Signature', 0, 1, 'C');

$pdf->Cell($colW, 5, 'of Applicant', 0, 0, 'C');
$pdf->Cell($colW, 5, 'of Parent', 0, 1, 'C');

$pdf->Ln(2);

// --- Signature boxes ---
$boxY = $pdf->GetY();

$boxW = 60;
$boxH = 25;

// Center boxes inside columns
$appBoxX = $leftX + ($colW - $boxW)/2;
$parBoxX = $rightX + ($colW - $boxW)/2;

$pdf->Rect($appBoxX, $boxY, $boxW, $boxH);
$pdf->Rect($parBoxX, $boxY, $boxW, $boxH);

// --- Signature images ---
if (!empty($stud_sign)) {
    $pdf->Image($stud_sign, $appBoxX + 2, $boxY + 2, $boxW - 4, $boxH - 4);
}

if (!empty($parent_sign)) {
    $pdf->Image($parent_sign, $parBoxX + 2, $boxY + 2, $boxW - 4, $boxH - 4);
}

// --- Labels below boxes ---
$pdf->SetY($boxY + $boxH + 2);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($colW, 5, "Applicant's Signature", 0, 0, 'C');
$pdf->Cell($colW, 5, "Parent's Signature", 0, 1, 'C');
// Page number
$pdf->SetY(-15);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 10, '2', 0, 0, 'R');

// Output
$pdf_file = $upload_dir . $name . ".pdf";
$pdf->Output($pdf_file, "F");
        // ================= MAIL =================
        require 'lib/PHPMailer/src/PHPMailer.php';
        require 'lib/PHPMailer/src/SMTP.php';
        require 'lib/PHPMailer/src/Exception.php';

        // STUDENT MAIL
        $mail = new PHPMailer(true);
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
        $mail->Body="Your OCI Application Submitted. Ref: $reference_no";
        $mail->addAttachment($pdf_file);
        $mail->send();

        // ADMIN MAIL
        $admin = new PHPMailer(true);
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
        $admin->Subject='New OCI Application';
        $admin->Body = "<h2 style='font-family:Arial;'>$name - $reference_no</h2>

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
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Total Marks</td><td style='padding:8px;border:1px solid #ddd;'>$total_marks</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Group Marks</td><td style='padding:8px;border:1px solid #ddd;'>$group_marks</td></tr>

<tr>
<th colspan='2' style='background:#4CAF50;color:white;padding:10px;text-align:left;'>Entrance Details</th>
</tr>

 <tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Place/Country</td><td style='padding:8px;border:1px solid #ddd;'>$place_country</td></tr>

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

 
<tr><td style='padding:8px;border:1px solid #ddd;'>Student Passport</td><td style='padding:8px;border:1px solid #ddd;'><a href='$passport_url' style='color:white;background:#007BFF;padding:5px 10px;text-decoration:none;border-radius:4px;'>Open</a></td></tr>

<tr><td style='padding:8px;border:1px solid #ddd;'>Adderss Proof/Driving License of Student</td><td style='padding:8px;border:1px solid #ddd;'><a href='$address_proof_url' style='color:white;background:#007BFF;padding:5px 10px;text-decoration:none;border-radius:4px;'>Open</a></td></tr>


</table>";

        // Attach all files
        $admin->addAttachment($pdf_file);
        $admin->addAttachment($ssc);
        $admin->addAttachment($inter);
        $admin->addAttachment($passport);
        $admin->addAttachment($address_proof);
        $admin->addAttachment($photo);
        $admin->addAttachment($stud_sign);
        $admin->addAttachment($parent_sign);

        $admin->send();

        header("Location: success.php?ref=".$reference_no);
        exit();
    }
}
?>