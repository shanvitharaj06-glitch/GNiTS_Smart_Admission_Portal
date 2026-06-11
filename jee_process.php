<?php
session_start();
include "db_connect.php";

/* ===============================
   GENERATE UNIQUE REFERENCE NO
================================= */
$reference_no = "JEE" . date("YmdHis") . rand(100,999);


/* ===============================
   SECURE FILE UPLOAD FUNCTION
================================= */
function uploadFile($file, $folder, $maxSize)
{
    $allowed = ['jpg','jpeg','png','pdf'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        die("Invalid file type: " . $file['name']);
    }

    if ($file['size'] > $maxSize) {
        die("File too large: " . $file['name']);
    }

    $filename = time() . "_" . uniqid() . "." . $ext;
    $destination = "uploads/$folder/" . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        die("File upload failed");
    }

    return $filename;
}


/* ===============================
   UPLOAD FILES WITH SIZE LIMIT
================================= */

$photo = uploadFile($_FILES['photo'], "photos", 200000); // 200KB
$tenth = uploadFile($_FILES['tenth'], "documents", 300000);
$twelfth = uploadFile($_FILES['inter'], "documents", 300000);
$jee_rank_card = uploadFile($_FILES['jee_card'], "documents", 300000);

$eamcet_rank_card = "";
if (!empty($_FILES['eamcet_card']['name'])) {
    $eamcet_rank_card = uploadFile($_FILES['eamcet_card'], "documents", 300000);
}

$student_signature = uploadFile($_FILES['stud_sign'], "signatures", 200000);
$parent_signature  = uploadFile($_FILES['parent_sign'], "signatures", 200000);


/* ===============================
   GET FORM DATA
================================= */

$name = $_POST['name'];
$dob = $_POST['dob'];
$father = $_POST['father'];
$mother = $_POST['mother'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$country = $_POST['country'];
$email = $_POST['email'];
$aadhaar = $_POST['aadhaar'];
$mobile = $_POST['mobile'];
$board = $_POST['board'];
$passing_year = $_POST['passing_year'];
$total_marks = $_POST['total_marks'];
$group_marks = $_POST['group_marks'];
$jee_rank = $_POST['jee_rank'];
$jee_percentile = $_POST['jee_percentile'];
$eamcet_rank = $_POST['eamcet_rank'];


/* ===============================
   INSERT INTO DATABASE
================================= */

$stmt = $conn->prepare("
INSERT INTO jee (
reference_no,name,dob,father,mother,
address1,address2,city,state,zip,country,
email,confim_email,aadhaar,mobile,board,passing_year,
total_marks,group_marks,jee_rank,jee_percentile,eamcet_rank,
photo,tenth,inter,jee_card,eamcet_card,
stud_sign,parent_sign
)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
"ssssssssssssssssssssssssssss",
$reference_no,$name,$dob,$father,$mother,
$address1,$address2,$city,$state,$zip,$country,
$email,$aadhaar,$mobile,$board,$passing_year,
$total_marks,$group_marks,$jee_rank,$jee_percentile,$eamcet_rank,
$photo,$tenth,$twelfth,$jee_rank_card,$eamcet_rank_card,
$student_signature,$parent_signature
);

$stmt->execute();


/* ===============================
   STORE SESSION FOR PAYMENT
================================= */

$_SESSION['reference_no'] = $reference_no;
$_SESSION['amount'] = 1;


/* ===============================
   REDIRECT TO RAZORPAY PAGE
================================= */

header("Location: payments/payu_payment.php");
exit();

?>
