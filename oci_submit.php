<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
function showPopup($message){
    echo "<script>
            alert(" . json_encode($message) . ");
            window.history.back();
          </script>";
    exit();
}
session_start();
ob_start();

include("db/db_connect.php");

// ==========================
// UPLOAD SETTINGS
// ==========================
$upload_dir = __DIR__ . "/uploads/";
$upload_url = "http://gnitscollege.in/uploads";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// ==========================
// UPLOAD FUNCTION
// ==========================
function uploadFile($file, $upload_dir, $type="doc"){

    if(!isset($file) || $file['error'] != 0) return null;

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $img_ext = ['jpg','jpeg','png'];
    $doc_ext = ['pdf'];

    if($type == "image" && !in_array($ext, $img_ext)) {
        showPopUp("Invalid image file");
    }

    if($type == "doc" && !in_array($ext, $doc_ext)) {
        showPopUp("Invalid document file");
    }

    $name = uniqid()."_".time().".".$ext;
    $path = $upload_dir . $name;

    move_uploaded_file($file['tmp_name'], $path);

    return $name;
}

// ==========================
// GET DATA
// ==========================
$name = $_POST['name'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$father = $_POST['father'];
$mother = $_POST['mother'];
$community = $_POST['community'];
$aadhar = $_POST['aadhar'];

$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$country = $_POST['country'];

$email = $_POST['email'];
$confirm_email = strtolower(trim($_POST['confirm_email']));

$mobile = $_POST['mobile'];

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

// OCI EXTRA
$citizenship = $_POST['place_country'];
  if (!in_array($gender, ['F','M','O'])) die("Invalid Gender");

    if (!in_array($community, ['SC','ST','OC','EWS','BC-A','BC-B','BC-C','BC-D','BC-E'])) showPopUp("Invalid Community");

    if ($inter_percentage < 0 || $inter_percentage > 100) showPopUp("Invalid Inter Percentage");

    if ($inter_group_percentage < 0 || $inter_group_percentage > 100) showPopUp("Invalid Group Percentage");

    if (empty($pref1) || empty($pref2) || empty($pref3) || empty($pref4) || empty($pref5)) {
        showPopUp("All preferences required");
    }

    if (count([$pref1,$pref2,$pref3,$pref4,$pref5]) != count(array_unique([$pref1,$pref2,$pref3,$pref4,$pref5]))) {
        showPopUp("Duplicate preferences not allowed");
    }
    
     if ($email !== $confirm_email) {
    echo "<script>alert('Email and Confirm Email do not match.');</script>";
}



// duplicate check
$check = $conn->prepare("SELECT id FROM oci WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    showPopup("This email is already registered. Duplicate entries are not allowed.");
    exit; // ⛔ STOP EXECUTION HERE
}
$check->close();


// ==========================
// FILE UPLOAD (reuse your function)
// ==========================
 

$photo = uploadFile($_FILES['photo'], "uploads/", "image");
$tenth = uploadFile($_FILES['ssc'], "uploads/");
$inter = uploadFile($_FILES['inter'], "uploads/");
$passport = uploadFile($_FILES['passport'], "uploads/");
$oci_card = uploadFile($_FILES['address_proof'], "uploads/");
$stud_sign = uploadFile($_FILES['stud_sign'], "uploads/", "image");
$parent_sign = uploadFile($_FILES['parent_sign'], "uploads/", "image");

// ==========================
// INSERT
// ==========================
$stmt = $conn->prepare("
INSERT INTO oci (
    name, gender, dob, father, mother, community,
    address1, address2, city, state, zip, country,
    email, mobile,aadhar,
    board, passing_year, total_marks, group_marks,
    intermediate_hall_ticket, inter_percentage, inter_group_percentage,
    pref1, pref2, pref3, pref4, pref5,
    place_country,
    photo, ssc, inter, passport, address_proof,stud_sign, parent_sign
) VALUES (
    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
)
");

$types = str_repeat("s", 35);

$stmt->bind_param(
    $types,
    $name, $gender, $dob, $father, $mother, $community,
    $address1, $address2, $city, $state, $zip, $country,
    $email, $mobile,$aadhar,
    $board, $passing_year, $total_marks, $group_marks,
    $intermediate_hall_ticket, $inter_percentage, $inter_group_percentage,
    $pref1, $pref2, $pref3, $pref4, $pref5,
    $citizenship,
    $photo, $tenth, $inter, $passport, $oci_card,$stud_sign, $parent_sign
);

$stmt->execute();

$last_id = $conn->insert_id;
$reference_no = "OCI" . $last_id;

$stmt = $conn->prepare("UPDATE oci SET reference_no=? WHERE id=?");
$stmt->bind_param("si", $reference_no, $last_id);
$stmt->execute();

$txnid = "TXN" . rand(100000,999999);

$stmt = $conn->prepare("UPDATE oci SET txnid=? WHERE id=?");
$stmt->bind_param("si", $txnid, $last_id);



    $stmt->execute();
    

    // ==========================
    // SESSION FOR PAYMENT
    // ==========================
  $_SESSION['reference_no'] = $reference_no;
$_SESSION['txnid'] = $txnid;
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['mobile'] = $mobile;

// REDIRECT PAYMENT
header("Location: payu_payment_oci.php");
exit();
?>