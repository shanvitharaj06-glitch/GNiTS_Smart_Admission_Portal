<?php
$host = "localhost";
//$user = "gnitsc79_gnitsc79";
//$pass = "@Gnits@123456@";
$dbname = "gnitsc79_gnits_db";

$conn = new mysqli($host, "root", " ", $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$error = "";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        if(password_verify($password, $row['password'])){
            $_SESSION['admin'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid Password!";
        }
    } else {
        $error = "❌ Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        /* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Body */
body {
    background: white;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Header */
.header {
    position: relative;
    display: flex;
    align-items: center;
    padding: 20px 30px;
    background-color: #003D3D;   /* Thick green */
}

/* Logo */
.logo {
    width: 80px;
    position: absolute;
    left: 30px;
}

/* College Name (centered) */
.college-name {
    margin: 0 auto;
    color: white;
    font-size: 22px;
    font-weight: bold;
    padding: 8px 20px;
    border-radius: 8px;
    text-align: center;
    animation: floatText 3s ease-in-out infinite;
}


/* Floating animation */
@keyframes floatText {
    0% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
    100% { transform: translateY(0); }
}

/* Center container */
.login-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn 1s ease;
}

/* Login box */
.login-form {
    width: 340px;
    padding: 35px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    text-align: center;
    animation: slideUp 0.8s ease;
}

.login-form h2 {
    margin-bottom: 25px;
    color: #333;
}

/* Inputs */
.input-group {
    margin-bottom: 18px;
}

.input-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    transition: 0.3s;
}

.input-group input:focus {
    border-color: orange;
    outline: none;
    box-shadow: 0 0 5px rgba(255,165,0,0.4);
}

/* Button */
.login-btn {
    width: 100%;
    padding: 10px;
    background: orange;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.login-btn:hover {
    background: #ff7a00;
    transform: scale(1.05);
}

/* Sign in text */
.signin-text {
    margin-top: 15px;
    font-size: 14px;
}

.signin-text a {
    color: orange;
    text-decoration: none;
    font-weight: bold;
}

.signin-text a:hover {
    text-decoration: underline;
}

/* Footer */
.footer {
    text-align: center;
    padding: 15px;
    background: #003D3D;
    color: #ffff;
    font-size: 14px;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        transform: translateY(40px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
.login-top-btn {
    position: absolute;
    right: 30px;
    background: orange;
    color: white;
    padding: 8px 18px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
}

.login-top-btn:hover {
    background: #ff7a00;
    transform: scale(1.05);
}
.main-container {
    display: flex;
    flex: 1;
    animation: fadeIn 1s ease;
}

/* Left Frame (20%) */
.left-frame {
    width: 20%;
    background: #f5f5f5;
    padding: 30px 20px;
    border-right: 3px solid orange;
    animation: slideUp 0.8s ease;
}

.left-frame h3 {
    margin-bottom: 20px;
    color: #006400;
}

/* Admission Links */
.admission-link {
    display: block;
    margin-bottom: 15px;
    padding: 10px;
    background: white;
    color: #006400;
    text-decoration: none;
    border: 2px solid orange;
    border-radius: 6px;
    transition: 0.3s;
}

.admission-link:hover {
    background: orange;
    color: white;
    transform: translateX(5px);
}

/* Right Frame (80%) */
.right-frame {
    width: 80%;
    padding: 50px;
    animation: slideUp 1s ease;
}

.right-frame h2 {
    color: #006400;
    margin-bottom: 20px;
}

    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <img src="pics/logo.jpg" alt="Logo" class="logo">
    <h1 class="college-name">
        G. NARAYANAMMA INSTITUTE OF TECHNOLOGY AND SCIENCE
    </h1>
</header>

<!-- Login Container -->
<div class="login-container">
    <form class="login-form" method="POST" action="">
        <h2>Admin Login Form</h2>

        <!-- ERROR MESSAGE -->
        <?php if($error != ""){ ?>
            <p style="color:red; text-align:center;"><?php echo $error; ?></p>
        <?php } ?>

        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" name="login" class="login-btn">Login</button>

        <p class="signin-text">
            Admin Access Only
        </p>
    </form>
</div>

<!-- Footer -->
<footer class="footer">
    © G. Narayanamma Institute of Technology and Science
</footer>

</body>
</html>