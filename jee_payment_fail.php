<?php
session_start();
include("db/db_connect.php");

function showPopup($message){
    echo "<script>alert(" . json_encode($message) . ");</script>";
}

// ==========================
// GET PAYU RESPONSE
// ==========================
$txnid = $_POST['txnid'] ?? '';
$email = $_POST['email'] ?? '';

// ==========================
// UPDATE PAYMENT STATUS
// ==========================
if (!empty($txnid)) {
    $stmt = $conn->prepare("
        UPDATE jee 
        SET payment_status='Failed', payu_status='failed'
        WHERE txnid=?
    ");
    $stmt->bind_param("s", $txnid);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Failed</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #ff4e50, #c0392b);
    margin: 0;
    padding: 0;
}

.container {
    width: 420px;
    margin: 120px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
}

h2 {
    color: #e74c3c;
    margin-bottom: 15px;
}

p {
    color: #555;
    font-size: 14px;
}

input[type="email"] {
    width: 90%;
    padding: 12px;
    margin: 15px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}

button {
    width: 95%;
    padding: 12px;
    background: #e74c3c;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #c0392b;
}

.retry {
    margin-top: 10px;
    font-size: 13px;
    color: #666;
}

.success-btn {
    margin-top: 10px;
    background: #3498db;
}

.success-btn:hover {
    background: #2980b9;
}
</style>

</head>
<body>

<div class="container">
    <h2>❌ Payment Failed</h2>

    <p>Your payment was not completed.</p>
    <p>Your application is safely saved.</p>

    <form action="jee_retry_payment.php" method="POST">
        <input type="email" name="email" 
               value="<?= htmlspecialchars($email) ?>" 
               placeholder="Enter your email" required>

        <button type="submit">🔄 Pay Again</button>
    </form>

    <div class="retry">
        Use the same email you used during application.
    </div>

    <form action="jee.php">
        <button class="success-btn">⬅ Back to Form</button>
    </form>
</div>

</body>
</html>