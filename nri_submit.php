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
include("db/db_connect.php");

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
        die("Invalid image file");
    }

    if($type == "doc" && !in_array($ext, $doc_ext)) {
        die("Invalid document file");
    }

    $name = uniqid()."_".time().".".$ext;
    $path = $upload_dir . $name;

    move_uploaded_file($file['tmp_name'], $path);

    return $name;
}
// ==========================
// GENERATE REFERENCE
// ==========================
 

// ==========================
// GET FORM DATA
// ==========================
if($_SERVER["REQUEST_METHOD"] == "POST"){
$name = $_POST['name'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$father = $_POST['father'];
$mother = $_POST['mother'];
$community = $_POST['community'];

$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$country = $_POST['country'];

$email = $_POST['email'];
$confirm_email = strtolower(trim($_POST['confirm_email']));

$aadhar = $_POST['aadhar'];
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

$relationship = $_POST['relationship'];
$sponsor_name = $_POST['sponsor_name'];
$place_country = $_POST['place_country'];
$eamcet_rank = $_POST['eamcet_rank'];

$payment_status = "Pending";

// ==========================
// VALIDATIONS
// ==========================
if (!in_array($gender, ['F','M','O'])) die("Invalid Gender");

if (!in_array($community, ['SC','ST','OC','EWS','BC-A','BC-B','BC-C','BC-D','BC-E'])) showPopUp("Invalid Community");

if ($inter_percentage < 0 || $inter_percentage > 100) showPopUp("Invalid Inter Percentage");

if ($inter_group_percentage < 0 || $inter_group_percentage > 100) showPopUp("Invalid Group Percentage");

$preferences = [$pref1,$pref2,$pref3,$pref4,$pref5];

if (count(array_filter($preferences)) != 5) {
    showPopUp("All preferences required");
}

if (count($preferences) != count(array_unique($preferences))) {
    showPopUp("Duplicate preferences not allowed");
}

 if ($email !== $confirm_email) {
    echo "<script>alert('Email and Confirm Email do not match.');</script>";
}



// duplicate check
$check = $conn->prepare("SELECT id FROM nri WHERE email = ?");
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
// UPLOAD FILES
// ==========================
$photo = uploadFile($_FILES['photo'],$upload_dir,"image");
$tenth = uploadFile($_FILES['tenth'],$upload_dir);
$inter = uploadFile($_FILES['inter'],$upload_dir);
$passport = uploadFile($_FILES['passport'],$upload_dir);
$eamcet_card = uploadFile($_FILES['eamcet_card'],$upload_dir);
$nri_letter = uploadFile($_FILES['nri_letter'],$upload_dir);
$nri_driving = uploadFile($_FILES['nri_driving'],$upload_dir);
$stud_sign = uploadFile($_FILES['stud_sign'],$upload_dir,"image");
$parent_sign = uploadFile($_FILES['parent_sign'],$upload_dir,"image");

// ==========================
// INSERT INTO DATABASE
// ==========================
$columns = [
"name","gender","dob","father","mother","community",
"address1","address2","city","state","zip","country",
"email","confirm_email","aadhar","mobile",
"board","passing_year","total_marks","group_marks",
"intermediate_hall_ticket","inter_percentage","inter_group_percentage",
"pref1","pref2","pref3","pref4","pref5",
"relationship","sponsor_name","place_country","eamcet_rank",
"photo","tenth","inter","passport","eamcet_card",
"nri_letter","nri_driving","stud_sign","parent_sign",
"payment_status"
];

// auto generate placeholders
$placeholders = implode(',', array_fill(0, count($columns), '?'));

// build query
$sql = "INSERT INTO nri (" . implode(',', $columns) . ") VALUES ($placeholders)";

$stmt = $conn->prepare($sql);

if(!$stmt){
    showPopUp("Prepare Error: " . $conn->error);
}

if (!$stmt) {
    showPopUp("Prepare Error: " . $conn->error);
}

// ==========================
// VALUES ARRAY
// ==========================
$values = [
$name,$gender,$dob,$father,$mother,$community,
$address1,$address2,$city,$state,$zip,$country,
$email,$confirm_email,$aadhar,$mobile,
$board,$passing_year,$total_marks,$group_marks,
$intermediate_hall_ticket,$inter_percentage,$inter_group_percentage,
$pref1,$pref2,$pref3,$pref4,$pref5,
$relationship,$sponsor_name,$place_country,$eamcet_rank,
$photo,$tenth,$inter,$passport,$eamcet_card,
$nri_letter,$nri_driving,$stud_sign,$parent_sign,
$payment_status
];

// ==========================
// AUTO BIND
// ==========================
$types = str_repeat("s", count($values));
$stmt->bind_param($types, ...$values);

// ==========================
// EXECUTE
// ==========================
if (!$stmt->execute()) {
    die("Execute Error: " . $stmt->error);
}

// Generate ref
$last_id = $conn->insert_id;
$reference_no = "NRI" . $last_id;

$stmt = $conn->prepare("UPDATE nri SET reference_no=? WHERE id=?");
$stmt->bind_param("si", $reference_no, $last_id);
$stmt->execute();

$txnid = "TXN" . rand(100000,999999);

$stmt = $conn->prepare("UPDATE nri SET txnid=? WHERE id=?");
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

// ==========================
// REDIRECT TO PAYMENT
// ==========================
header("Location:payu_payment_nri.php");

exit();
}
?>