<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] != true){
    header("Location: index.php");
    exit();
}

// PREVENT BACK BUTTON CACHE
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$host = "localhost";
$user = "gnitsc79_gnitsc79";
$pass = "@Gnits@123456@";
$dbname = "gnitsc79_gnits_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ ONLY APPROVED NRI STUDENTS
$sql = "SELECT * FROM nri WHERE status='Approved' ORDER BY eamcet_rank ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Approved NRI Students</title>
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<style>
body{
    font-family:'Segoe UI';
    background:#f4f7f9;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 20px;
    background: linear-gradient(135deg,#2c3e50,#4ca1af);
    color:white;
    position:relative;
}

.header .title{
    position:absolute;
    left:50%;
    transform:translateX(-50%);
}

/* BUTTONS */
.btn{
    padding:8px 15px;
    border-radius:25px;
    text-decoration:none;
    color:white;
}

.logout{background:#e74c3c;}
.back{background:#16a085;}

/* CONTROLS */
.controls{
    text-align:center;
    margin:15px;
}

.controls a{
    padding:10px 18px;
    background:#3498db;
    color:white;
    border-radius:25px;
    text-decoration:none;
}

/* TABLE */
table{
    width:95%;
    margin:auto;
    border-collapse:collapse;
    background:white;
}

th{
    background:#3498db;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
}

/* STATUS */
.status{
    background:green;
    color:white;
    padding:5px 10px;
    border-radius:15px;
}
</style>
<script>
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>
</head>

<body>

<!-- HEADER -->
<div class="header">

    <a href="dashboard.php" class="btn back">⬅ Back</a>

    <h2 class="title">Approved NRI Students</h2>

    <a href="logout.php" class="btn logout">Logout</a>

</div>

<!-- PDF EXPORT -->
<div class="controls">
    <a href="export_pdf.php?type=approved_nri">📄 Export Approved PDF</a>
</div>

<table>
<thead>
<tr>
    <th>Ref No</th>
    <th>Name</th>
    <th>EAMCET Rank</th>
    <th>Email</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php while($row = $result->fetch_assoc()){ ?>
<tr>
    <td><?= $row['reference_no'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['eamcet_rank'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><span class="status"><?= $row['status'] ?></span></td>
</tr>
<?php } ?>
</tbody>
</table>

</body>
</html>