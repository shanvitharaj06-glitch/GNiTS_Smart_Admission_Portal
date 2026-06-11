<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

/* ENABLE OPTIONS */
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

/* BASE URL */
$base = "http://gnitscollege.in/";

/* IMAGE PATHS */
$photo = !empty($_SESSION['photo']) ? $base . "nri_uploads/" . $_SESSION['photo'] : "";
$stud_sign = !empty($_SESSION['stud_sign']) ? $base . "nri_uploads/" . $_SESSION['stud_sign'] : "";
$parent_sign = !empty($_SESSION['parent_sign']) ? $base . "nri_uploads/" . $_SESSION['parent_sign'] : "";
$logo = "http://gnitscollege.in/pics/logo.jpg";

/* SIMPLE HTML (IMPORTANT) */
$html = '
<table width="100%" style="border-bottom:2px solid black;">
<tr>

<td width="20%" align="left">
    <img src="'.$logo.'" width="90" height="90">
</td>

<td width="60%" align="center">
    <h3 style="margin:0; color:black;">
        G NARAYANAMMA INSTITUTE OF TECHNOLOGY AND SCIENCE
    </h3>
    <h4 style="margin:5px 0;">NRI ADMISSION FORM</h4>
</td>

</tr>
</table>
<br>




<table border="1" width="100%" cellspacing="0" cellpadding="8">

<tr>
<td width="70%">
<b>Name:</b> '.$_SESSION['name'].'<br>
<b>DOB:</b> '.$_SESSION['dob'].'<br>
<b>Father:</b> '.$_SESSION['father'].'<br>
<b>Mother:</b> '.$_SESSION['mother'].'<br>
<b>Email:</b> '.$_SESSION['email'].'<br>
<b>Mobile:</b> '.$_SESSION['mobile'].'
</td>

<td align="center">
'.($photo ? '<img src="'.$photo.'" width="100" height="120">' : 'Photo').'
</td>
</tr>

</table>

<br>

<h3>Documents</h3>

<table border="1" width="100%" cellpadding="8">
<tr><td>Photo</td><td>'.(!empty($_SESSION['photo']) ? 'Yes' : 'No').'</td></tr>
<tr><td>10th</td><td>'.(!empty($_SESSION['tenth']) ? 'Yes' : 'No').'</td></tr>
<tr><td>12th</td><td>'.(!empty($_SESSION['inter']) ? 'Yes' : 'No').'</td></tr>
<tr><td>Passport</td><td>'.(!empty($_SESSION['passport']) ? 'Yes' : 'No').'</td></tr>
<tr><td>EAMCET</td><td>'.(!empty($_SESSION['eamcet_card']) ? 'Yes' : 'No').'</td></tr>
<tr><td>NRI Sponsership letter </td><td>'.(!empty($_SESSION['nri_letter']) ? 'Yes' : 'No').'</td></tr>
<tr><td>NRI Driving licence/Adderss proof</td><td>'.(!empty($_SESSION['nri_driving']) ? 'Yes' : 'No').'</td></tr>
</table>

<br><br>

<table width="100%">
<tr>
<td align="center">
'.($stud_sign ? '<img src="'.$stud_sign.'" width="100" height="50"><br>Student Signature' : 'Student Signature').'
</td>

<td align="center">
'.($parent_sign ? '<img src="'.$parent_sign.'" width="100" height="50"><br>Parent Signature' : 'Parent Signature').'
</td>
</tr>
</table>
';

/* GENERATE PDF */
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Admission_Form.pdf", ["Attachment" => true]);
?>