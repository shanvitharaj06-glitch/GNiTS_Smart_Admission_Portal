<?php
session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] != true){
    header("Location: index.php");
    exit();
}


header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

/* 🌈 BODY */
body{
    display:flex;
    background:#eef2f3;
}

/* 📌 SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    background:#2c3e50;
    color:white;
    position:fixed;
    padding-top:20px;

    overflow-y:auto;   /* ✅ enables vertical scroll */
    scrollbar-width: thin; /* optional (Firefox) */
}
/* Chrome, Edge */
.sidebar::-webkit-scrollbar {
    width:6px;
}

.sidebar::-webkit-scrollbar-track {
    background:#2c3e50;
}

.sidebar::-webkit-scrollbar-thumb {
    background:#888;
    border-radius:10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background:#aaa;
}
.sidebar{
    scroll-behavior:smooth;
}

.sidebar h2{
    text-align:center;
    margin-bottom:20px;
}

.sidebar a{
    display:block;
    padding:15px;
    color:white;
    text-decoration:none;
    transition:0.3s;
}

.sidebar a:hover{
    background:#34495e;
    padding-left:25px;
}

/* 🎓 HEADER */
.header{
    position:fixed;
    left:240px;
    width:calc(100% - 240px);
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 20px;
    background: linear-gradient(135deg,#2c3e50,#4ca1af);
    color:white;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);
}

.header-center{
    display:flex;
    align-items:center;
    gap:15px;
}

.header-actions{
    display:flex;
    align-items:center;
    gap:10px;
}

.change-pass{
    background:#16a085;
}

.change-pass:hover{
    background:#138d75;
}
.logo{
    width:50px;
    height:50px;
    border-radius:50%;
}

.college-name{
    font-size:15px;
    font-weight:bold;
}

/* 🔘 BUTTONS */
.btn{
    padding:8px 15px;
    border-radius:20px;
    text-decoration:none;
    color:white;
    transition:0.3s;
}

.logout{
    background:#e74c3c;
}

.btn:hover{
    transform:scale(1.1);
}

/* 📊 MAIN CONTENT */
.main{
    margin-left:240px;
    margin-top:80px;
    padding:20px;
    width:100%;
}

/* 📦 CARDS */
.cards{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.card{
    flex:1;
    min-width:200px;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-10px);
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.card h3{
    margin-bottom:10px;
    color:#2c3e50;
}

.card p{
    font-size:22px;
    font-weight:bold;
}

/* 🎬 ANIMATION */
.card{
    animation:fadeIn 0.6s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
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

<!-- 📌 SIDEBAR -->
<div class="sidebar">
    <h2>Admin Panel</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="jee_list.php">📘 JEE Students</a>
    <a href="nri_list.php">🌍 NRI Students</a>
    <a href="oci_list.php">🌍 OCI Students</a>
    
    <h2>Download Details</h2>
    <a href="export_excel_all.php">Overall Admissions Date Wise</a>
    <a href="jee_merit_list.php">JEE Merit List</a>
    <a href="nri_merit_list.php">NRI Merit List</a>
    <a href="oci_merit_list.php">OCI Merit List</a>
    <a href="export_nri_branch.php?course=ALL">NRI Overall Merit Branch Wise</a>
    <a href="export_jee_branch.php?course=ALL">JEE Overall Merit Branch Wise</a>
    <a href="export_oci_branch.php?course=ALL">OCI Overall Merit Branch Wise</a>
     
     
</div>

<!-- 🎓 HEADER -->
<div class="header">

    <div class="header-center">
        <img src="pics/logo.jpg" class="logo">
        <div class="college-name">
           <h1> G. NARAYANAMMA INSTITUTE OF TECHNOLOGY AND SCIENCE</h1>
        </div>
    </div>
 <a href="change_password.php" class="btn change-pass">🔑 Change Password</a>
    <a href="logout.php" class="btn logout">Logout</a>

</div>

<!-- 📊 MAIN -->
<div class="main">

    <h2>Dashboard Overview</h2>
    <br>

    <div class="cards">

        <div class="card">
            <h3>Total JEE Students</h3>
            <p>
                <?php
                include "db/db_connect.php";
                $res = $conn->query("SELECT COUNT(*) as total FROM jee");
                $row = $res->fetch_assoc();
                echo $row['total'];
                ?>
            </p>
        </div>

        <div class="card">
            <h3>Total NRI Students</h3>
            <p>
                <?php
                $res = $conn->query("SELECT COUNT(*) as total FROM nri");
                $row = $res->fetch_assoc();
                echo $row['total'];
                ?>
            </p>
        </div>
        <div class="card">
            <h3>Total OCI Students</h3>
            <p>
                <?php
                include "db/db_connect.php";
                $res = $conn->query("SELECT COUNT(*) as total FROM oci");
                $row = $res->fetch_assoc();
                echo $row['total'];
                ?>
            </p>
        </div>

        <div class="card">
            <h3>Top JEE Rank</h3>
            <p>
                <?php
                $res = $conn->query("SELECT MIN(jee_rank) as rank1 FROM jee");
                $row = $res->fetch_assoc();
                echo $row['rank1'];
                ?>
            </p>
        </div>
         

        <div class="card">
            <h3>Top EAMCET Rank</h3>
            <p>
                <?php
                $res = $conn->query("SELECT MIN(eamcet_rank) as rank2 FROM nri");
                $row = $res->fetch_assoc();
                echo $row['rank2'];
                ?>
            </p>
        </div>

    </div>

</div>

</body>
</html>