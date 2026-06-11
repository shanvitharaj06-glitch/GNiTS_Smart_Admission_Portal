<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pay Pending Fee</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #4CAF50, #2E7D32);
    margin: 0;
    padding: 0;
}

.container {
    width: 400px;
    margin: 120px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #2E7D32;
}

input[type="email"] {
    width: 90%;
    padding: 12px;
    margin: 15px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

button {
    width: 95%;
    padding: 12px;
    background: #4CAF50;
    border: none;
    color: #fff;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #2E7D32;
}

.note {
    font-size: 13px;
    color: #666;
    margin-top: 10px;
}
</style>

</head>
<body>

<div class="container">
    <h2>💳 Pay Pending Application Fee For OCI</h2>

    <form action="oci_retry_payment.php" method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <button type="submit">Proceed to Payment</button>
    </form>

    <div class="note">
        Use the same email you used while submitting the application.
    </div>
</div>

</body>
</html>