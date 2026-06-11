<?php
session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] != true){
    header("Location: index.php");
    exit();
}


// 🚫 PREVENT BACK BUTTON CACHE
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// DB CONNECTION

$host = "localhost";
$user = "gnitsc79_gnitsc79";
$pass = "@Gnits@123456@";
$dbname = "gnitsc79_gnits_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// 📊 TYPE (Normal / Merit)
$type = $_GET['type'] ?? "normal";

// 🔍 STATUS FILTER
$filter = $_GET['filter'] ?? "all";

// 🧠 BASE QUERY
if($type == "merit"){
    $sql = "SELECT * FROM oci ORDER BY group_marks";
} else {
    $sql = "SELECT * FROM oci ORDER BY id DESC";
}

// 🧠 APPLY FILTER
if($filter == "approved"){
    $sql = "SELECT * FROM oci WHERE status='Approved'";
}
elseif($filter == "rejected"){
    $sql = "SELECT * FROM oci WHERE status='Rejected'";
}
elseif($filter == "pending"){
    $sql = "SELECT * FROM oci WHERE status='Pending'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>GNITS - OCI Students</title>
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI';}

body{background: linear-gradient(135deg,#eef2f3,#ffffff);}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:12px 20px;
    background: linear-gradient(135deg,#2c3e50,#4ca1af);
    color:white;
}
.header-center{
    display:flex;
    align-items:center;
    gap:15px;
}
.btn{
    padding:8px 15px;
    border-radius:25px;
    text-decoration:none;
    color:white;
}
.logo{width:55px;height:55px;border-radius:50%;}
.college-name{font-size:26px;font-weight:bold;}

.logout{background:#e74c3c;}
.back{background:#16a085;}

.controls.single-line{
    display:flex;
    justify-content:center;   /* ✅ CENTER ALIGN */
    align-items:center;
    gap:10px;
    padding:10px;
    margin:15px auto;
    overflow-x:auto;
    white-space:nowrap;
    max-width:100%;
}

/* buttons */
.controls.single-line a{
    flex-shrink:0;
    padding:8px 14px;
    border-radius:20px;
    background:#3498db;
    color:white;
    text-decoration:none;
    font-size:14px;
    transition:0.3s;
}

.controls.single-line a:hover{
    background:#2980b9;
}

/* SEARCH */
.search-box{
    text-align:center;
    margin:15px;
}

.search-box input{
    padding:12px;
    width:300px;
    border-radius:30px;
    border:1px solid #ccc;
}

/* TABLE */
.table-container{
    width:95%;
    margin:auto;
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th{
    background:#3498db;
    color:white;
    padding:12px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
}

/* STATUS */
.status{
    padding:6px 12px;
    border-radius:20px;
    color:white;
    font-size:13px;
}

.status.pending{background:orange;}
.status.approved{background:green;}
.status.rejected{background:red;}

/* BUTTONS */
.approve-btn{
    background:green;
    color:white;
    padding:6px 10px;
    border-radius:15px;
    text-decoration:none;
}

.reject-btn{
    background:red;
    color:white;
    padding:6px 10px;
    border-radius:15px;
    text-decoration:none;
}

.disabled{
    color:gray;
    font-weight:bold;
}
form[id^="branchBox"] {
    margin-top: 8px;
    padding: 6px;
    background: #f8f9fa;
    border-radius: 8px;
    display: inline-block;
}

select[name="branch"] {
    padding: 5px 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 13px;
    margin-right: 5px;
}

form button[type="submit"] {
    background: #2980b9;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 15px;
    cursor: pointer;
    font-size: 12px;
}
form[id^="branchBox"] {
    margin-top: 10px;
    padding: 10px 12px;
    background: #f4f6f8;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* DROPDOWN */
select[name="branch"] {
    padding: 7px 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    outline: none;
    transition: 0.2s;
}

select[name="branch"]:focus {
    border-color: #2980b9;
    box-shadow: 0 0 4px rgba(41, 128, 185, 0.4);
}

/* SUBMIT BUTTON */
form button[type="submit"] {
    background: linear-gradient(135deg, #2980b9, #3498db);
    color: white;
    border: none;
    padding: 7px 14px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s ease;
}

form button[type="submit"]:hover {
    background: linear-gradient(135deg, #1f6691, #2c81ba);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* RESEND BUTTON */
.resend-btn {
    background: linear-gradient(135deg, #8e44ad, #a569bd);
    color: white;
    padding: 7px 14px;
    border-radius: 20px;
    text-decoration: none;
    display: inline-block;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.resend-btn:hover {
    background: linear-gradient(135deg, #732d91, #8e44ad);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* DISABLED TEXT */
.disabled {
    color: #aaa;
    font-size: 13px;
    font-style: italic;
}
</style>

<script>
function searchTable() {
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function confirmAction(msg){
    return confirm(msg);
}

window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});

function showBranch(id){
    document.getElementById("branchBox" + id).style.display = "inline-block";
}
</script>


</head>

<body>

<!-- HEADER -->
<header class="header">
    <a href="dashboard.php" class="btn back">⬅ Back</a>
    <div class="header-center">
        <img src="pics/logo.jpg" class="logo">
        <h1 class="college-name">
            GNITS - OCI ADMISSIONS LIST
        </h1>
    </div>
    <a href="logout.php" class="btn logout">Logout</a>
</header>

<!-- 🎛️ MAIN CONTROLS -->
<div class="controls single-line">

    <!-- TYPE -->
    <a href="?type=normal">Normal</a>
    <a href="?type=merit">Merit</a>
    <a href="oci_merit_list.php">Merit List Download</a>

    <!-- FILTER -->
    <a href="?filter=all">All</a>
    <a href="?filter=pending">Pending</a>
    <a href="?filter=approved">Approved</a>
    <a href="?filter=rejected">Rejected</a>

    <!-- EXPORT -->
    <a href="export_pdf.php?type=oci">📄 PDF</a>
    <a href="export_excel.php?type=oci">📊 Excel</a>

</div>

<!-- SEARCH -->
<div class="search-box">
    <input type="text" id="search" onkeyup="searchTable()" placeholder="🔍 Search student...">
</div>

<!-- TABLE -->
<div class="table-container">
<table>

<thead>
<tr>
    <th>Ref No</th>
    <th>Name</th>
    <th>DOB</th>
    <th>Father</th>
    <th>Marks</th>
    <th>City</th>
    <th>Mobile</th>
    <th>Payment Status</th>
    <th>Resend Mail</th>
</tr>
</thead>


<tbody>
<?php while($row = $result->fetch_assoc()){ ?>
<tr>
    <td><?= $row['reference_no'] ?></td>

   <td>
<?php if($row['payment_status'] == "Paid"){ ?>
    
    <!-- ENABLED LINK -->
    <a href="oci_pdf.php?type=nri&id=<?= $row['id'] ?>">
        <?= $row['name'] ?>
    </a>

<?php } else { ?>
    
    <!-- DISABLED (NO LINK) -->
    <span class="disabled"><?= $row['name'] ?></span>

<?php } ?>
</td>

    <td><?= $row['dob'] ?></td>
    <td><?= $row['father'] ?></td>
    <td><?= $row['total_marks'] ?></td>
    <td><?= $row['city'] ?></td>
    <td><?= $row['mobile'] ?></td>
    <td><?= $row['payment_status'] ?></td>

    <td>
<?php if($row['payment_status'] == "Paid"){ ?>
    <a href="resend_mail_oci.php?id=<?= $row['id'] ?>"
       class="resend-btn"
       onclick="return confirm('Send approval mail again to this student?')">
       Resend Mail
    </a>
<?php } else { ?>
    <span class="disabled">Not Available</span>
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>

</table>
</div>

</body>
</html>