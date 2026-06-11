<?php
$host = "localhost";
//$user = "gnitsc79_gnitsc79";
//$pass = "@Gnits@123456@";
$dbname = "gnitsc79_gnits_db";

$conn = new mysqli($host, root," ", $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>