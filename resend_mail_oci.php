<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

include("db/db_connect.php");

require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';
require 'lib/PHPMailer/src/Exception.php';

require "download_pdf.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ==========================
// SEND MAIL FUNCTION
// ==========================
function sendMail($to, $subject, $body, $attachments = [])
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shanvitharaj06@gmail.com';
        $mail->Password   = 'imjz bewy fart cpnd';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('shanvitharaj06@gmail.com', 'GNITS Admissions');
        $mail->addAddress($to);

        foreach ($attachments as $file) {
            if (!empty($file) && file_exists($file)) {
                $mail->addAttachment($file);
            }
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}

// ==========================
// GET STUDENT ID FROM URL
// ==========================
$id = $_GET['id'] ?? 0;

if (empty($id)) {
    die("Student ID missing");
}

// ==========================
// FETCH STUDENT DATA
// ==========================
$stmt = $conn->prepare("SELECT * FROM oci WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user) {
    die("Student not found");
}

// ==========================
// STUDENT DETAILS
// ==========================
$name   = $user['name'];
$email  = $user['email'];
$ref    = $user['reference_no'];
$txnid  = $user['txnid'] ?? '';
$amount = $user['payment_amount'] ?? '';

// ==========================
// GENERATE PDF
// ==========================
$pdf_file = generateOciPdf($user['id'], $conn);

if (!$pdf_file) {
    die("PDF generation failed");
}

// ==========================
// STUDENT MAIL BODY
// ==========================
$studentBody = "
<div style='font-family:Arial,sans-serif;line-height:1.6;'>
    <h2 style='color:green;'>Application Submitted Successfully</h2>

    <p>Dear {$name},</p>

    <p>Your OCI application has been submitted successfully.</p>

    <p><b>Reference Number:</b> {$ref}</p>
    <p><b>Transaction ID:</b> {$txnid}</p>
    <p><b>Amount Paid:</b> ₹{$amount}</p>

    <p>Please find your application PDF attached with this email for reference.</p>

    <br>
    <p>Regards,<br><b>GNITS Admissions Team</b></p>
</div>
";

// ==========================
// SEND MAIL TO STUDENT ONLY
// ==========================
if (sendMail($email, "OCI Application Submitted", $studentBody, [$pdf_file])) {
    echo "
    <script>
        alert('Mail resent successfully to $email');
        window.location.href='oci_list.php';
    </script>
    ";
} else {
    echo "Mail sending failed.";
}
?>