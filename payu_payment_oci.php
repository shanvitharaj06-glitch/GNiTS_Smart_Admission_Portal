<?php
session_start();

// ==========================
// CHECK SESSION
// ==========================
if (!isset($_SESSION['reference_no'])) {
    die("Session expired");
}

// ==========================
// PAYU CONFIG
// ==========================
$merchantKey = "yADG45";
$salt = "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDubtm1f58rB4GRlp92Q8IcYpIU8IPWj60uqaivQCESnPLliYi90HAw7KiqRdap4A/BOuOL3Dh2AM//tCsfrzmUIP0m7OOgWWV5WvVI6xBvWiCiBLBaFHxpTTmnGQPspqPjOLNtGO8AOhk+VuMNWFCcAExT7Fa2y1cAZIrxr/nVTOcGCAbr9Vn8OjmgFn4KjtOZr1s65MY/qbWK+Kktvaqb/uZRJ3boN0PNmUZSJJOxNiDUcxe+aYCCMI+we8x0Tp1KDsC2tcjODV4jEcjKFs7R2bHGpVbVGIz6b2vvCHqRSF8E981/CRMDWs9yGaK088hDwaWalZfgk9CQsXWcRRtlAgMBAAECggEAU0QlBYxfIqDJb/WBFMUO+ei2iVeBQyID4eLgBOvJYP2smdqRBXDQbVWIhYZENCkKd3nAU2yBzzvzZ6Ic2UDRQ9jNb5WU3L+7f0jzlhZ5M9nuwYYPrDWxSPjuJFvJEhET9NstVfE6LgMcI7gmLNfltsUn4rsLgWS7qmhyWXsSLZmz/9w79kosSEO8u542Nm7gJN1JMDhEMrWmti5m/WEALN47bIeGaw4L2a+VVmbY13NVCmtXSfdk988KxFCPDZf4Cp6zWwtoRJBQF1KWi+1ilb9wjPLADEm0Djf2vNWshT34pxwTPW/QhJyoJUVF0DcIMV904zTGNsc9N4gAsWzmtQKBgQD/gASM1867JR6DrE8qCJmv65Aky3OB41tcbwrqqj81j7epYSKdCDiKbQVvqek2ObUZuPC17VaRs8RYbnVEiTfOLaHkTYiB3Fo51Sh4yvXrB4t4WoKJHvpNF2TIBCYJGOHSt7ZoXYl2DHTImYP3M+QFzYSvWZ0iC4r8lZxTlED3swKBgQDu5kiaxIr944j1XpPMiMBwRPl2G6uED8b9LaJGQxD1DhXzssSxOIWtSfHRYLi9SekRmiVg/Ew/XHofwOAYBJpMH6CMhxP7xHiDCZg4SftbaqAe0bfh6WrJjrnTjBKUyJVIIxnLe40rm2CzGhIHNSuhCQ+8inwV0mDkAxPgrnWUhwKBgDEEQVs358xVgbYnL4TT45AxdBTrBzzq/lMMZp7AjKHc8ZJINVjFA/vikIFsqnYhuhG2Pk/YuZv4TfndLxg37wHaFU30ZfTr7k9cCoip/2XYq7QqQRLHY6O4kjghO57RLDm9zvvUvhNsrlbxxLR/Owa19/egDJpEdqSgmz4ZmiErAoGBAN2e+gnBfONsunhN5bSfxE4iXn3Hy4Q2krX6KIkf3FJJX+n0lG2HbtNEPrWrEZZgQ3vV0Qk45I7+/jgI2JrPYkhuKqVTBiHQsK93LiRB1ZGHx8TvbrN/s1YKNq9eSTqHHZ2PGnXfRyYF/V4JOGJBXBkG6/dPTS99O+8qP712epNvAoGAFtw7OMPMLnXNJq87nEhTkOnfZxz7fphYMjurSW+7IM5xrRGhDZBSgN0UsA0l2uoxFeI8lvEaJ1kyrIHjwVefKm8xoWgT+pW1wI70aUly64ZqcM6HbM6xg88BqzBkSj2j0yODRUfy46w+K1rKbXhR5brhTxwhRxZV81AG7R8piaM=";


$amount = 1; // OCI fee
$productinfo = "OCI ADMISSION FEE";

// ==========================
// SESSION DATA
// ==========================
$txnid = $_SESSION['txnid'] ?? '';
$name = $_SESSION['name'] ?? '';
$email = $_SESSION['email'] ?? '';
$mobile = $_SESSION['mobile'] ?? '';
$amount = "1";
$productinfo = "JEE Application";

if (empty($txnid) || empty($name) || empty($email)) {
    die("Payment session data missing");
}


var_dump($merchantKey);
var_dump($salt);

// ==========================
// PAYU HASH GENERATION
// ==========================
$hashString = $merchantKey."|".$txnid."|".$amount."|".$productinfo."|".$name."|".$email."|||||||||||".$salt;

$hash = strtolower(hash('sha512', $hashString));
 

// ==========================
// CALLBACK URL (CHANGE THIS)
// ==========================
$baseUrl = "http://gnitscollege.in/";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Payment...</title>
</head>

<body onload="document.forms[0].submit();">

<h3>Redirecting to Payment Gateway...</h3>

<form action="https://secure.payu.in/_payment"; method="post">
    <input type="hidden" name="key" value="<?php echo $merchantKey; ?>">
    <input type="hidden" name="txnid" value="<?php echo $txnid; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
    <input type="hidden" name="productinfo" value="<?php echo $productinfo; ?>">
    <input type="hidden" name="firstname" value="<?php echo $name; ?>">
    <input type="hidden" name="email" value="<?php echo $email; ?>">
    <input type="hidden" name="phone" value="<?php echo $mobile; ?>">

    <!-- SUCCESS & FAILURE URL -->
    <input type="hidden" name="surl" value="<?= $baseUrl ?>/payu_success_oci.php">
<input type="hidden" name="furl" value="<?= $baseUrl ?>/payments/payu_failure.php">

    <input type="hidden" name="hash" value="<?= $hash ?>">

</form>

</body>
</html>