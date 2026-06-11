<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db/db_connect.php");

// 🔒 Check admin session
if(!isset($_SESSION['admin']) || $_SESSION['admin'] != true){
    header("Location: index.php");
    exit();
}

$msg = "";
$success = false;

if(isset($_POST['update'])){

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // 🔍 Get admin password
    $res = $conn->query("SELECT * FROM admins WHERE username='Admin'");
    $row = $res->fetch_assoc();

    if($row){

        // ✅ Verify current password
        if(password_verify($current, $row['password'])){

            if($new === $confirm){

                // 🔒 Hash new password
                $hashed = password_hash($new, PASSWORD_DEFAULT);

                // ✅ Update password (FIXED username case)
                $conn->query("UPDATE admins SET password='$hashed' WHERE username='Admin'");

                $msg = "✅ Password updated successfully!";
                $success = true;

                // 🔓 Destroy session (force re-login)
                session_destroy();

            } else {
                $msg = "❌ New passwords do not match!";
            }

        } else {
            $msg = "❌ Current password is incorrect!";
        }

    } else {
        $msg = "❌ Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
<style>
body{
    font-family:Arial;
    background:#f4f6f8;
    text-align:center;
}
.box{
    width:350px;
    margin:80px auto;
    padding:20px;
    background:white;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
input{
    width:90%;
    padding:10px;
    margin:10px;
    border-radius:5px;
    border:1px solid #ccc;
}
button{
    padding:10px 20px;
    background:#3498db;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
button:hover{
    opacity:0.9;
}
.msg{
    margin-top:10px;
    font-weight:bold;
}
.login-btn{
    background:#2ecc71;
    margin-top:10px;
}
</style>
</head>

<body>

<div class="box">
<h2>Change Password</h2>

<form method="POST">
    <input type="password" name="current_password" placeholder="Current Password" required><br>
    <input type="password" name="new_password" placeholder="New Password" required><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
    <button type="submit" name="update">Update Password</button>
</form>

<div class="msg"><?= $msg ?></div>

<!-- ✅ Show Login Button only after success -->
<?php if($success){ ?>
    <br>
    <a href="login.php">
        <button class="login-btn">Go to Login</button>
    </a>
<?php } ?>

</div>

<!-- ✅ Optional Auto Redirect -->
<?php if($success){ ?>
<script>
    setTimeout(() => {
        window.location.href = "login.php";
    }, 2000); // 2 seconds
</script>
<?php } ?>

</body>
</html>