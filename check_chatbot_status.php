<?php
include("db/db_connect.php");

$reference_no = $_GET['reference_no'] ?? '';

if(empty($reference_no)){
    echo "Please provide reference number.";
    exit();
}

/*
   Check in jee table
*/
$stmt = $conn->prepare("SELECT name, payment_status FROM jee WHERE reference_no = ?");
$stmt->bind_param("s", $reference_no);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    echo "Hello <b>" . htmlspecialchars($row['name']) . "</b>, your JEE application Payment status is: <b>" . htmlspecialchars($row['payment_status']) . "</b>.";
    exit();
}

/*
   Check in nri table
*/
$stmt = $conn->prepare("SELECT name, payment_status FROM nri WHERE reference_no = ?");
$stmt->bind_param("s", $reference_no);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    echo "Hello <b>" . htmlspecialchars($row['name']) . "</b>, your NRI application Payment status is: <b>" . htmlspecialchars($row['payment_status']) . "</b>.";
    exit();
}

/*
   Check in oci table
*/
$stmt = $conn->prepare("SELECT name, payment_status FROM oci WHERE reference_no = ?");
$stmt->bind_param("s", $reference_no);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    echo "Hello <b>" . htmlspecialchars($row['name']) . "</b>, your OCI application Payment status is: <b>" . htmlspecialchars($row['payment_status']) . "</b>.";
    exit();
}

echo "Reference number not found. Please check and try again.";
?>