<?php
use PHPMailer\PHPMailer\PHPMailer;

require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';
require 'lib/PHPMailer/src/Exception.php';

function sendMail($to, $subject, $body,$attachments = [])
{
    try{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'shanvitharaj06@gmail.com';
    $mail->Password = 'imjz bewy fart cpnd';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('yourgmail@gmail.com', 'Admission System');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;

    // ✅ MULTIPLE ATTACHMENTS SUPPORT
  //  foreach ($attachments as $file) {
//    if (!empty($file) && file_exists($file)) {
 //       $mail->addAttachment($file);
  //  } else {
    //    echo "Missing file: " . $file . "<br>";
//    }
//}
if (!is_array($attachments)) {
            $attachments = [$attachments];
        }

        foreach ($attachments as $file) {
  

            if (!empty($file) && file_exists($file)) {
         
                $mail->addAttachment($file, basename($file));
            } else {
                echo "Missing: " . $file . "<br>";
            }
        }

        

        $mail->send();
        return true;
    } 
    catch(Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        exit();
    }
}
?>