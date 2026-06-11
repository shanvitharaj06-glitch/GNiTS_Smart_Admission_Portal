<?php
ob_start();
session_start();
include("db/db_connect.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "download_pdf.php";
require "mail_helper.php";
 
$upload_dir = __DIR__ . "/uploads/";
$upload_url = "http://gnitscollege.in/uploads/";

// ==========================
// CHECK PAYU RESPONSE
// ==========================
$status      = $_POST['status'] ?? '';
$txnid       = $_POST['txnid'] ?? '';
$amount      = $_POST['amount'] ?? '';
$posted_hash = $_POST['hash'] ?? '';
$key         = $_POST['key'] ?? '';
$productinfo = $_POST['productinfo'] ?? '';
$email       = $_POST['email'] ?? '';

if ($status !== "success") {
    die("Payment not successful");
}

if (empty($txnid)) {
    die("Transaction ID missing");
}

// ==========================
// GET USER USING TXNID
// ==========================
$stmt = $conn->prepare("SELECT * FROM oci WHERE txnid = ?");
$stmt->bind_param("s", $txnid);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    die("User not found");
}

$ref = $user['reference_no'];

// ==========================
// UPDATE PAYMENT STATUS
// ==========================
$stmt = $conn->prepare("
    UPDATE oci
    SET payment_status='Paid',
        payu_status='success',
        payment_amount=?,
        payment_date=NOW()
    WHERE txnid=?
");
$stmt->bind_param("ds", $amount, $txnid);
$stmt->execute();

// refresh latest user data after update
$stmt = $conn->prepare("SELECT * FROM oci WHERE txnid = ?");
$stmt->bind_param("s", $txnid);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

// ==========================
// GENERATE PDF
// ==========================
$pdf_file = generateOciPdf($user['id'], $conn);

if (!$pdf_file) {
    die("PDF generation failed");
}

// ==========================
// EXTRACT DATA
// ==========================
$name = $user['name'];
$gender = $user['gender'];
$dob = $user['dob'];
$community = $user['community'];
$father = $user['father'];
$mother = $user['mother'];
$mobile = $user['mobile'];
$email = $user['email'];
$aadhar = $user['aadhar'];

$address1 = $user['address1'];
$address2 = $user['address2'];
$city = $user['city'];
$state = $user['state'];
$zip = $user['zip'];
$country = $user['country'];

$board = $user['board'];
$passing_year = $user['passing_year'];
$intermediate_hall_ticket = $user['intermediate_hall_ticket'];
$inter_percentage = $user['inter_percentage'];
$inter_group_percentage = $user['inter_group_percentage'];
$total_marks = $user['total_marks'];
$group_marks = $user['group_marks'];

 
$place_country = $user['place_country'];
 

$pref1 = $user['pref1'];
$pref2 = $user['pref2'];
$pref3 = $user['pref3'];
$pref4 = $user['pref4'];
$pref5 = $user['pref5'];

// ==========================
// FILE PATHS
// ==========================
$ssc_url    = $upload_url . $user['ssc'];
$inter_url  = $upload_url . $user['inter'];
$passport_url  = $upload_url . $user['passport'];
$address_proof_url  = $upload_url . $user['address_proof'];
 
// ==========================
// ATTACHMENTS
$attachments = [];

$files = [
    $pdf_file,
    $upload_dir . basename($user['ssc'] ?? ''),
    $upload_dir . basename($user['inter'] ?? ''),
    $upload_dir . basename($user['passport'] ?? ''),
    $upload_dir . basename($user['address_proof'] ?? ''),
    $upload_dir . basename($user['photo'] ?? ''),
    $upload_dir . basename($user['stud_sign'] ?? ''),
    $upload_dir . basename($user['parent_sign'] ?? '')
];

foreach ($files as $file) {
    if (!empty($file) && $file !== $upload_dir && file_exists($file)) {
        $attachments[] = $file;
    }
}
// ==========================
// STUDENT MAIL
// ==========================
$studentBody = "
<h2>Payment Successful 🎉</h2>
<p><b>Reference No:</b> $ref</p>
<p><b>Transaction ID:</b> $txnid</p>
<p><b>Amount:</b> $amount</p>
<p>Your OCI application is successfully submitted.</p>
";

// ==========================
// ADMIN MAIL (JEE FORMAT)
// ==========================
$adminBody = "<h2 style='font-family:Arial;'>$name - $ref</h2>

<table style='border-collapse:collapse;width:100%;font-family:Arial,sans-serif;font-size:14px;border:1px solid #ddd;'>

<tr>
<th colspan='2' style='background:#4CAF50;color:white;padding:10px;text-align:left;'>Personal Details</th>
</tr>

<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Name</td><td style='padding:8px;border:1px solid #ddd;'>$name</td></tr>
<tr><td style='padding:8px;font-weight:bold;background:#f9f9f9;border:1px solid #ddd;'>Reference No</td><td style='padding:8px;border:1px solid #ddd;'>$ref</td></tr>
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


// ==========================
// SEND MAILS
// ==========================
sendMail($email, "OCI Application Submitted", $studentBody, [$pdf_file]);

sendMail(
    "shanvitharaj06@gmail.com",
    "New OCI Paid Application - ".$user['reference_no'],
    $adminBody,
    $attachments
);

// ==========================
// REDIRECT
// ==========================
header("Location: success.php?ref=".$ref);
exit();
?>