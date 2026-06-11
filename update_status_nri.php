<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db/db_connect.php";

require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';
require 'lib/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
$action = $_POST['action'] ?? ($_GET['action'] ?? '');
$branch = $_POST['branch'] ?? '';

if ($id <= 0) {
    die("Invalid student ID");
}

$result = $conn->query("SELECT * FROM nri WHERE id=$id");

if (!$result || $result->num_rows == 0) {
    die("Student not found!");
}

$row = $result->fetch_assoc();
$name = $row['name'];
$email = $row['email'];

if ($action == "approve") {

    if (empty($branch)) {
        die("Please select a branch!");
    }

    $status = "Approved";

   $conn->query("UPDATE nri SET status='$status', allotted_branch='$branch' WHERE id=$id");

    if (!$conn) {
        die("Database update failed: " . $conn->error);
    }

    $subject = "Admission Approved - GNITS";
    $body = "
    <h3>Dear $name,</h3>
    <p>Congratulations!</p>
    <p>You have been <b>SELECTED</b> for admission at GNITS.</p>
    <p>Your allotted branch is <b>$branch</b>.</p>
    <p>We welcome you to our institution.</p>
    <br>
    <p>Regards,<br>GNITS Admission Team</p>
    ";

} elseif ($action == "reject") {

    $status = "Rejected";

   $conn->query("UPDATE nri SET status='$status', allotted_branch=NULL WHERE id=$id");

    if (!$update) {
        die("Database update failed: " . $conn->error);
    }

    $subject = "Admission Result - GNITS";
    $body = "
    <h3>Dear $name,</h3>
    <p>Thank you for applying.</p>
    <p>We regret to inform you that you are <b>NOT SELECTED</b>.</p>
    <br>
    <p>We wish you all the best for your future.</p>
    <br>
    <p>Regards,<br>GNITS Admission Team</p>
    ";

} else {
    die("Invalid action!");
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'shanvitharaj06@gmail.com';
    $mail->Password = 'imjz bewy fart cpnd';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('shanvitharaj06@gmail.com', 'GNITS Admissions');
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();

    header("Location: nri_list.php");
    exit();

} catch (Exception $e) {
    die("Mailer Error: " . $mail->ErrorInfo);
}
?>