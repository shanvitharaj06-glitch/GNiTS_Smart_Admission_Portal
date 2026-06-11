<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DATABASE CONNECTION
include "db/db_connect.php";

// INCLUDE PHPMailer
require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';
require 'lib/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// GET DATA FROM POST OR GET
$id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
$action = $_POST['action'] ?? ($_GET['action'] ?? '');
$branch = $_POST['branch'] ?? '';

// FETCH STUDENT DATA
$result = $conn->query("SELECT * FROM jee WHERE id=$id");

if (!$result || $result->num_rows == 0) {
    die("Student not found!");
}

$row = $result->fetch_assoc();

$name = $row['name'];
$email = $row['email'];

// DECIDE STATUS + MESSAGE
if ($action == "approve") {

    if (empty($branch)) {
        die("Please select a branch!");
    }

    $status = "Approved";

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

    $update = $conn->query("UPDATE jee SET status='$status', allotted_branch='$branch' WHERE id=$id");

    if (!$update) {
        die("Database update failed: " . $conn->error);
    }

} elseif ($action == "reject") {

    $status = "Rejected";

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

    $update = $conn->query("UPDATE jee SET status='$status', allotted_branch=NULL WHERE id=$id");

    if (!$update) {
        die("Database update failed: " . $conn->error);
    }

} else {
    die("Invalid action!");
}

// SEND EMAIL
$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 0;

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

} catch (Exception $e) {
    echo "Mail Error: " . $mail->ErrorInfo;
}

// REDIRECT
header("Location: jee_list.php");
exit();
?>