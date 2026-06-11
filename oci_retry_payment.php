<?php
include("db/db_connect.php");

function showPopup($message){
    echo "<script>alert(" . json_encode($message) . "); window.history.back();</script>";
    exit();
}

$email = strtolower(trim($_POST['email'] ?? ''));

if ($email == '') {
    showPopup("Please enter email address.");
}

// find pending application by email
$stmt = $conn->prepare("SELECT * FROM oci WHERE email = ? AND payment_status = 'Pending' LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

if (!$user) {
    showPopup("No pending application found for this email.");
}

// create new txnid
$new_txnid = "TXN" . rand(10000,99999) . time();

// update txnid before retry payment
$update = $conn->prepare("UPDATE oci SET txnid = ?, payu_status = 'retry' WHERE id = ?");
$update->bind_param("si", $new_txnid, $user['id']);
$update->execute();
$update->close();

// PayU details
$MERCHANT_KEY = "yADG45";
$SALT = "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDubtm1f58rB4GRlp92Q8IcYpIU8IPWj60uqaivQCESnPLliYi90HAw7KiqRdap4A/BOuOL3Dh2AM//tCsfrzmUIP0m7OOgWWV5WvVI6xBvWiCiBLBaFHxpTTmnGQPspqPjOLNtGO8AOhk+VuMNWFCcAExT7Fa2y1cAZIrxr/nVTOcGCAbr9Vn8OjmgFn4KjtOZr1s65MY/qbWK+Kktvaqb/uZRJ3boN0PNmUZSJJOxNiDUcxe+aYCCMI+we8x0Tp1KDsC2tcjODV4jEcjKFs7R2bHGpVbVGIz6b2vvCHqRSF8E981/CRMDWs9yGaK088hDwaWalZfgk9CQsXWcRRtlAgMBAAECggEAU0QlBYxfIqDJb/WBFMUO+ei2iVeBQyID4eLgBOvJYP2smdqRBXDQbVWIhYZENCkKd3nAU2yBzzvzZ6Ic2UDRQ9jNb5WU3L+7f0jzlhZ5M9nuwYYPrDWxSPjuJFvJEhET9NstVfE6LgMcI7gmLNfltsUn4rsLgWS7qmhyWXsSLZmz/9w79kosSEO8u542Nm7gJN1JMDhEMrWmti5m/WEALN47bIeGaw4L2a+VVmbY13NVCmtXSfdk988KxFCPDZf4Cp6zWwtoRJBQF1KWi+1ilb9wjPLADEm0Djf2vNWshT34pxwTPW/QhJyoJUVF0DcIMV904zTGNsc9N4gAsWzmtQKBgQD/gASM1867JR6DrE8qCJmv65Aky3OB41tcbwrqqj81j7epYSKdCDiKbQVvqek2ObUZuPC17VaRs8RYbnVEiTfOLaHkTYiB3Fo51Sh4yvXrB4t4WoKJHvpNF2TIBCYJGOHSt7ZoXYl2DHTImYP3M+QFzYSvWZ0iC4r8lZxTlED3swKBgQDu5kiaxIr944j1XpPMiMBwRPl2G6uED8b9LaJGQxD1DhXzssSxOIWtSfHRYLi9SekRmiVg/Ew/XHofwOAYBJpMH6CMhxP7xHiDCZg4SftbaqAe0bfh6WrJjrnTjBKUyJVIIxnLe40rm2CzGhIHNSuhCQ+8inwV0mDkAxPgrnWUhwKBgDEEQVs358xVgbYnL4TT45AxdBTrBzzq/lMMZp7AjKHc8ZJINVjFA/vikIFsqnYhuhG2Pk/YuZv4TfndLxg37wHaFU30ZfTr7k9cCoip/2XYq7QqQRLHY6O4kjghO57RLDm9zvvUvhNsrlbxxLR/Owa19/egDJpEdqSgmz4ZmiErAoGBAN2e+gnBfONsunhN5bSfxE4iXn3Hy4Q2krX6KIkf3FJJX+n0lG2HbtNEPrWrEZZgQ3vV0Qk45I7+/jgI2JrPYkhuKqVTBiHQsK93LiRB1ZGHx8TvbrN/s1YKNq9eSTqHHZ2PGnXfRyYF/V4JOGJBXBkG6/dPTS99O+8qP712epNvAoGAFtw7OMPMLnXNJq87nEhTkOnfZxz7fphYMjurSW+7IM5xrRGhDZBSgN0UsA0l2uoxFeI8lvEaJ1kyrIHjwVefKm8xoWgT+pW1wI70aUly64ZqcM6HbM6xg88BqzBkSj2j0yODRUfy46w+K1rKbXhR5brhTxwhRxZV81AG7R8piaM=";
$amount = "1";
$productinfo = "NRI Admission Fee";
$firstname = $user['name'];
$phone = $user['mobile'];

$surl = "http://gnitscollege.in/payu_success_oci.php";
$furl = "http://gnitscollege.in/oci_payment_fail.php";

$hashString = $MERCHANT_KEY . "|" . $new_txnid . "|" . $amount . "|" . $productinfo . "|" . $firstname . "|" . $email . "|||||||||||" . $SALT;
$hash = strtolower(hash("sha512", $hashString));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Payment</title>
</head>
<body>
    <form action="https://secure.payu.in/_payment" method="post" name="payuForm">
        <input type="hidden" name="key" value="<?= $MERCHANT_KEY ?>">
        <input type="hidden" name="txnid" value="<?= $new_txnid ?>">
        <input type="hidden" name="amount" value="<?= $amount ?>">
        <input type="hidden" name="productinfo" value="<?= $productinfo ?>">
        <input type="hidden" name="firstname" value="<?= htmlspecialchars($firstname) ?>">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <input type="hidden" name="phone" value="<?= htmlspecialchars($phone) ?>">
        <input type="hidden" name="surl" value="<?= $surl ?>">
        <input type="hidden" name="furl" value="<?= $furl ?>">
        <input type="hidden" name="hash" value="<?= $hash ?>">
    </form>

    <script>
        document.payuForm.submit();
    </script>
</body>
</html>