<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function showPopup($message){
    echo "<script>
            alert(".json_encode($message).");
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
        echo "<script>alert('Invalid image file. Only JPG, JPEG, PNG allowed');</script>";
    }

    if($type == "doc" && !in_array($ext, $doc_ext)) {
        showPopup("Invalid document file");
    }

    $name = uniqid()."_".time().".".$ext;
    $path = $upload_dir . $name;

    move_uploaded_file($file['tmp_name'], $path);

    return $name;
}

// ==========================
// FORM SUBMIT
// ==========================
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // ==========================
    // BASIC DATA
    // ==========================
    

    $name = strtoupper($_POST['name']);
    $dob = $_POST['dob'];
    $father = $_POST['father'];
    $mother = $_POST['mother'];

    $gender = $_POST['gender'];
    $community = $_POST['community'];

    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $country = $_POST['country'];

    $email = strtolower(trim($_POST['email']));
$confirm_email = strtolower(trim($_POST['confirm_email']));
    $aadhar = $_POST['aadhar'];
    $mobile = $_POST['mobile'];

    $board = $_POST['board'];
    $passing_year = $_POST['passing_year'];

    $intermediate_hall_ticket = $_POST['intermediate_hall_ticket'];
    $inter_percentage = $_POST['inter_percentage'];
    $inter_group_percentage = $_POST['inter_group_percentage'];

    $total_marks = $_POST['total_marks'];
    $group_marks = $_POST['group_marks'];

    $jee_hall_ticket = $_POST['jee_hall_ticket'];
    $jee_rank = $_POST['jee_rank'];
    $jee_percentile = $_POST['jee_percentile'];

    $eamcet_hall_ticket = $_POST['eamcet_hall_ticket'];
    $eamcet_rank = $_POST['eamcet_rank'];

    $pref1 = $_POST['pref1'];
    $pref2 = $_POST['pref2'];
    $pref3 = $_POST['pref3'];
    $pref4 = $_POST['pref4'];
    $pref5 = $_POST['pref5'];

    // ==========================
    // VALIDATIONS
    // ==========================

    if (!in_array($gender, ['F','M','O'])) die("Invalid Gender");

    if (!in_array($community, ['SC','ST','OC','EWS','BC-A','BC-B','BC-C','BC-D','BC-E'])) showPopup("Invalid Community");

    if ($inter_percentage < 0 || $inter_percentage > 100) 
    showPopup("Invalid Inter Percentage");

    if ($inter_group_percentage < 0 || $inter_group_percentage > 100) 
    showPopup("Invalid Group Percentage");

    if (empty($pref1) || empty($pref2) || empty($pref3) || empty($pref4) || empty($pref5)) {
        showPopup("All preferences required");
    }

    if (count([$pref1,$pref2,$pref3,$pref4,$pref5]) != count(array_unique([$pref1,$pref2,$pref3,$pref4,$pref5]))) {
        
        showPopup("Duplicate preferences not allowed");
    }

    // ==========================
    // FILE UPLOADS
    // ==========================
    $photo = uploadFile($_FILES['photo'], $upload_dir, "image");
    $tenth = uploadFile($_FILES['tenth'], $upload_dir, "doc");
    $inter = uploadFile($_FILES['inter'], $upload_dir, "doc");
    $jee_card = uploadFile($_FILES['jee_card'], $upload_dir, "doc");
    $eamcet_card = uploadFile($_FILES['eamcet_card'], $upload_dir, "doc");
    $stud_sign = uploadFile($_FILES['stud_sign'], $upload_dir, "image");
    $parent_sign = uploadFile($_FILES['parent_sign'], $upload_dir, "image");
    
    if ($email !== $confirm_email) {
    echo "<script>alert('Email and Confirm Email do not match.');</script>";
}



// duplicate check
$check = $conn->prepare("SELECT id FROM jee WHERE email = ?");
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
    // INSERT INTO DB
    // ==========================
    $stmt = $conn->prepare("INSERT INTO jee (
     name, gender, dob, father, mother, community,
    address1, address2, city, state, zip, country,
    email, confirm_email, aadhar, mobile,
    board, passing_year,
    intermediate_hall_ticket, inter_percentage, inter_group_percentage,
    total_marks, group_marks,
    jee_hall_ticket, jee_rank, jee_percentile,
    eamcet_hall_ticket, eamcet_rank,
    pref1, pref2, pref3, pref4, pref5,
    photo, tenth, inter, jee_card, eamcet_card,
    stud_sign, parent_sign,
    payment_status, payu_status
) VALUES (
   ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, 'Pending','Pending'
)");

// ✅ All as string (SAFE METHOD)
$types = str_repeat("s", 40);

$stmt->bind_param(
    $types,
    $name,
    $gender,
    $dob,
    $father,
    $mother,
    $community,

    $address1,
    $address2,
    $city,
    $state,
    $zip,
    $country,

    $email,
    $confirm_email,
    $aadhar,
    $mobile,

    $board,
    $passing_year,

    $intermediate_hall_ticket,
    $inter_percentage,
    $inter_group_percentage,

    $total_marks,
    $group_marks,

    $jee_hall_ticket,
    $jee_rank,
    $jee_percentile,

    $eamcet_hall_ticket,
    $eamcet_rank,

    $pref1,
    $pref2,
    $pref3,
    $pref4,
    $pref5,

    $photo,
    $tenth,
    $inter,
    $jee_card,
    $eamcet_card,
    $stud_sign,
    $parent_sign
);

 
  if (!$stmt->execute()) {
        die("Insert failed: " . $stmt->error);
    }


// Generate ref
$last_id = $conn->insert_id;
$reference_no = "JEE" . $last_id;

$stmt = $conn->prepare("UPDATE jee SET reference_no=? WHERE id=?");
$stmt->bind_param("si", $reference_no, $last_id);
$stmt->execute();

$txnid = "TXN" . rand(100000,999999);

$stmt = $conn->prepare("UPDATE jee SET txnid=? WHERE id=?");
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
    // GO TO PAYMENT
    // ==========================
    header("Location: payu_payment_jee.php?type=jee");
    exit();
}
?>