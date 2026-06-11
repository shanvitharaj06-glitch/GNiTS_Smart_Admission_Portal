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

// ONLY APPROVED STUDENTS
$sql = "SELECT * FROM jee WHERE status='Approved' ORDER BY jee_rank ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Approved JEE Students</title>
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7f9;
    margin: 0;
    padding: 0;
}

/* HEADER */
.header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px 20px;
    background: linear-gradient(135deg,#2c3e50,#4ca1af);
    color:white;
    position:relative;
}

/* CENTER TITLE */
.header .title{
    position:absolute;
    left:50%;
    transform:translateX(-50%);
    font-size:18px;
    margin: 0;
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

<div class="header">
    <a href="dashboard.php" class="btn back">⬅ Back</a>
    <h2 class="title">Approved JEE Students</h2>
    <a href="logout.php" class="btn logout">Logout</a>
</div>

<div class="controls">
    <a href="export_pdf.php?type=approved_jee">📄 Export Approved PDF</a>
</div>

<table>
<thead>
<tr>
    <th>Ref No</th>
    <th>Name</th>
    <th>JEE Rank</th>
    <th>Email</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php if($result && $result->num_rows > 0){ ?>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?= htmlspecialchars($row['reference_no']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['jee_rank']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><span class="status"><?= htmlspecialchars($row['status']) ?></span></td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="5">No approved students found</td>
    </tr>
<?php } ?>
</tbody>
</table>

</body>
</html>