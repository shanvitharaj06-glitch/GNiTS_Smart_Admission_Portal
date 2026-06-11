<?php
session_start();

/* ===============================
   TEMP FILE UPLOAD FUNCTION
================================= */
function tempUpload($file, $folder)
{
    if (!empty($file['name'])) {

        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die("Invalid file type: " . $file['name']);
        }

        $filename = time() . "_" . uniqid() . "." . $ext;
        $destination = "jee_uploads/" . $filename;

        move_uploaded_file($file['tmp_name'], $destination);

        return $filename;
    }
    return "";
}
function temp($file, $folder)
{
    if (!empty($file['name'])) {

        $allowed = ['pdf'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die("Invalid file type: " . $file['name']);
        }

        $filename = time() . "_" . uniqid() . "." . $ext;
        $destination = "jee_uploads/" . $filename;

        move_uploaded_file($file['tmp_name'], $destination);

        return $filename;
    }
    return "";
}

/* ===============================
   STORE POST DATA INTO SESSION
================================= */

foreach ($_POST as $key => $value) {
    $_SESSION[$key] = htmlspecialchars($value);
}

/* ===============================
   HANDLE FILES
================================= */

$_SESSION['photo'] = tempUpload($_FILES['photo'], "photo");
$_SESSION['tenth'] = temp($_FILES['tenth'], "doc");
$_SESSION['inter'] = temp($_FILES['inter'], "doc");
$_SESSION['jee_card'] = temp($_FILES['jee_card'], "doc");
$_SESSION['eamcet_card'] = temp($_FILES['eamcet_card'], "doc");
$_SESSION['stud_sign'] = tempUpload($_FILES['stud_sign'], "sign");
$_SESSION['parent_sign'] = tempUpload($_FILES['parent_sign'], "sign");

?>
<!DOCTYPE html>
<html>
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<title>Admission Form Preview</title>

<style>
body {
    font-family: "Times New Roman", serif;
    background: #f2f2f2;
}

/* Document Style */
.document {
    width: 800px;
    margin: 30px auto;
    background: white;
    padding: 40px;
    border: 2px solid black;
}
.header {
    display: flex;
    align-items: center;
    background-color: #003D3D;
    color: white;
    padding: 15px;
    border-radius: 5px 5px 0 0;
}

/* Logo */
.logo {
    width: 80px;
    height: 80px;
    margin-right: 15px;
}

/* Text Section */
.header-text {
    flex: 1;
    text-align: center;
}

.college-name {
    margin: 0;
    font-size: 20px;
    font-weight: bold;
}

.header-text h3 {
    margin: 5px 0 0;
    font-weight: normal;
}
/* Section Title */
.section-title {
    margin-top: 20px;
    font-weight: bold;
    border-bottom: 1px solid black;
    padding-bottom: 5px;
}
.download-btn {
    background: green;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* Table Format */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

td {
    padding: 8px;
    border: 1px solid #ccc;
}

.label {
    font-weight: bold;
    width: 30%;
}

/* Buttons */
.button-group {
    text-align: center;
    margin-top: 30px;
}

button {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

.edit-btn {
    background: grey;
    color: white;
}

.pay-btn {
    background: orange;
    color: white;
}
#admissionForm {
    width: 210mm;   /* Exact A4 width */
    padding: 10mm;
    background: white;
}

/* Prevent breaking important sections */
table, tr, td {
    page-break-inside: avoid;
}

/* Optional: Avoid breaking sections */
.section-title {
    page-break-before: auto;
    page-break-after: avoid;
}
</style>

</head>
<body>

<div class="document" id="admissionForm">

<!-- HEADER -->
 <header class="header">
    
    <!-- Logo Left -->
    <img src="pics/logo.jpg" alt="Logo" class="logo">

    <!-- Text Center -->
    <div class="header-text">
        <h1 class="college-name">
            G. NARAYANAMMA INSTITUTE OF TECHNOLOGY AND SCIENCE
        </h1>
        <h3>JEE ADMISSION FORM</h3>
    </div>
</header>
<!-- PERSONAL DETAILS -->
<div class="section-title">1. Personal Details</div>

<table>
<tr>
    <td class="label">Full Name</td>
    <td><?= $_SESSION['name'] ?></td>
</tr>
<tr>
    <td class="label">Date of Birth</td>
    <td><?= $_SESSION['dob'] ?></td>
</tr>
<tr>
    <td class="label">Father Name</td>
    <td><?= $_SESSION['father'] ?></td>
</tr>
<tr>
    <td class="label">Mother Name</td>
    <td><?= $_SESSION['mother'] ?></td>
</tr>
<tr>
    <td class="label">Email</td>
    <td><?= $_SESSION['email'] ?></td>
</tr>
<tr>
    <td class="label">Mobile</td>
    <td><?= $_SESSION['mobile'] ?></td>
</tr>
</table>

<!-- DOCUMENTS -->
<div class="section-title">2. Uploaded Documents</div>

<table>
<tr>
    <td class="label">Photo</td>
<td><?= !empty($_SESSION['photo']) ? "Yes" : "No" ?></td>
</tr>

<tr>
    <td class="label">10th Memo</td>
    <td><?= !empty($_SESSION['tenth']) ? "Yes" : "No" ?></td>
</tr>

<tr>
    <td class="label">12th Memo</td>
    <td><?= !empty($_SESSION['inter']) ? "Yes" : "No" ?></td>
</tr>

<tr>
    <td class="label">JEE RankCard</td>
    <td><?= !empty($_SESSION['jee_card']) ? "Yes" : "No" ?></td>
</tr>

<tr>
    <td class="label">EAMCET Rank Card</td>
    <td><?= !empty($_SESSION['eamcet_card']) ? "Yes" : "No" ?></td>
</tr>

</table>

<!-- SIGNATURES -->
<div class="section-title">3. Signatures</div>

<table>
<tr>
    <td class="label">Student Signature</td>
    <td><?= !empty($_SESSION['stud_sign']) ? "Yes" : "No" ?></td>
</tr>

<tr>
    <td class="label">Parent Signature</td>
    <td><?= !empty($_SESSION['parent_sign']) ? "Yes" : "No" ?></td>
</tr>
</table>

<!-- BUTTONS -->
<div class="button-group">

<button onclick="window.history.back()" class="edit-btn">
    Edit Application
</button>
    <form action="download_jee_pdf.php" method="POST">
    <button type="submit" class="download-btn">
        Download Application Form
    </button>
</form>
</div>

</div>

</body>
</html>
