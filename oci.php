<!DOCTYPE html>
<html>
<head>
<title>GNITS OCI Admission</title>
<link rel="stylesheet" href="css/admission.css">
<link rel="stylesheet" href="css/login.css">
 

<script>
// File size validation
function validateFile(input, maxKB, label = "File") {
    let file = input.files[0];
    if (!file) return;

    let sizeKB = file.size / 1024;
    let fileName = file.name.toLowerCase();
    let ext = fileName.split('.').pop();

    // Size check
    if (sizeKB > maxKB) {
        alert(label + " must be less than " + maxKB + "KB");
        input.value = "";
        return;
    }

    // Type check by extension
    if (maxKB == 200) {
        if (!['jpg', 'jpeg', 'png'].includes(ext)) {
            alert(label + " must be JPG, JPEG, or PNG");
            input.value = "";
            return;
        }
    }

    if (maxKB == 300) {
        if (ext !== 'pdf') {
            alert(label + " must be a PDF file");
            input.value = "";
            return;
        }
    }
}

// Name uppercase
function toUpperCaseInput(el){
    el.value = el.value.toUpperCase();
}

document.querySelector("form").addEventListener("submit", function(e) {

    let email = document.querySelector("input[name='email']").value;
    let confirm = document.querySelector("input[name='confirm_email']").value;

    if (email !== confirm) {
        alert("Email and Confirm Email do not match!");
        e.preventDefault();
        return;
    }

    let photo = document.querySelector("input[name='photo']").files[0];
    if (photo && photo.size > 200000) {
        alert("Photo must be less than 200KB");
        e.preventDefault();
        return;
    }

    let tenth = document.querySelector("input[name='tenth']").files[0];
    if (tenth && tenth.size > 300000) {
        alert("10th memo must be less than 300KB");
        e.preventDefault();
        return;
    }

});

function checkPreferences() {
    let prefs = [
        document.getElementById("pref1").value,
        document.getElementById("pref2").value,
        document.getElementById("pref3").value,
        document.getElementById("pref4").value,
        document.getElementById("pref5").value
    ];

    // Remove empty values
    let filledPrefs = prefs.filter(p => p !== "");

    // Check duplicates
    let uniquePrefs = [...new Set(filledPrefs)];

    if (filledPrefs.length !== uniquePrefs.length) {
        alert("Duplicate preferences are not allowed");

        // Clear the last changed field
        event.target.value = "";
    }
}

document.querySelector("form").addEventListener("submit", function(e) {

    let name = document.querySelector("input[name='name']").value;

    let pattern = /^[A-Z ]{3,100}$/;

    if (!pattern.test(name)) {
        alert("Name must be in BLOCK LETTERS (A-Z only)");
        e.preventDefault();
    }

});

document.querySelector("form").addEventListener("submit", function(e) {

    let code = document.querySelector("input[name='area_code']").value;
    let mobile = document.querySelector("input[name='mobile']").value;

    if (code !== "+91") {
        alert("Area code must be +91");
        e.preventDefault();
        return;
    }

    let pattern = /^[6-9][0-9]{9}$/;

    if (!pattern.test(mobile)) {
        alert("Invalid Mobile Number");
        e.preventDefault();
    }

});

function validateAge() {
    let dob = document.getElementById("dob").value;

    if (!dob) return;

    let birthDate = new Date(dob);
    let today = new Date();

    let age = today.getFullYear() - birthDate.getFullYear();
    let m = today.getMonth() - birthDate.getMonth();

    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    if (age < 15) {
        alert("Age should be above 15 years");
        document.getElementById("dob").value = "";
    }
}

</script>

</head>
<body>

<header class="header">
    <img src="pics/logo.jpg" alt="Logo" class="logo">
    <h1 class="college-name">
       <center> G. NARAYANAMMA INSTITUTE OF TECHNOLOGY AND SCIENCE</center>
    </h1>
</header>


<div class="form-container">
    <img src="pics/collage_header.png" alt="HEADER" class="logo1" width="550" height="150">
<form method="POST" action="insert_nri.php" enctype="multipart/form-data">

<!-- PERSONAL -->
<h2>Application for Admission into First Year B.Tech course under Foreign Nationals / Overseas
Citizen of India / Children of Indian Workers in Gulf Countries for the A.Y 2025-26</h2>

<label>Name of Applicant <span class="required">*</span></label>
<input type="text" name="name" required oninput="toUpperCaseInput(this)">
<label style="text-align:left;">
  Date of Birth <span class="required">*</span>
</label>

<input type="date" name="dob" id="dob" required onchange="validateAge()">


<label>Father Name <span class="required">*</span></label>
<input type="text" name="father" required oninput="toUpperCaseInput(this)">

<label>Mother Name <span class="required">*</span></label>
<input type="text" name="mother" required oninput="toUpperCaseInput(this)">

<label style="text-align:left;">Gender <span class="required">*</span></label>
<select name="gender" required>
    <option value="">Select</option>
    <option value="F">Female</option>
    <option value="M">Male</option>
    <option value="O">Other</option>
</select>

<!-- NEW: Community -->
<label style="text-align:left;">Community <span class="required">*</span></label>
<select name="community" required>
    <option value="">Select</option>
    <option value="OC">OC</option>
    <option value="EWS">EWS</option>
    <option value="OBC">OBC</option>
    <option value="BC_A">BC-A</option>
    <option value="BC_B">BC-B</option>
    <option value="BC_C">BC-C</option>
    <option value="BC_D">BC-D</option>
    <option value="BC_E">BC-E</option>
    <option value="SC">SC</option>
    <option value="ST">ST</option>
</select>


<!-- CONTACT -->
<h3>Contact Details</h3>

<label>Mobile Number <span class="required">*</span></label>
<div style="display:flex; gap:10px;">
<input type="text" name="area_code" value="+91" readonly style="width:80px;">
<input type="text" name="mobile" required pattern="[6-9][0-9]{9}" maxlength="10">
</div>

<label>Aadhar Number <span class="required">*</span></label>
<input type="text" name="aadhar" required pattern="[0-9]{12}">

<label>Email <span class="required">*</span></label>
<input type="email" name="email" required>

<label style="text-align:left;">Confirm Email Address <span class="required">*</span></label>
<input type="email" name="confirm_email" required>

<!-- ADDRESS -->
<h3>Address</h3>

<label style="text-align:left;">Address for Communication <span class="required">*</span></label>
<input type="text" name="address1" required placeholder="Address Line 1">
<input type="text" name="address2" placeholder="Address Line 2">

<input type="text" name="city" placeholder="City" required>
<input type="text" name="state" placeholder="State" required>
<input type="text" name="zip" placeholder="Zip Code" required>

<select name="country" required>
<option value="India">India</option>
</select>

<!-- ACADEMIC -->
<h3>Qualifying Examination Passed (12th or Equivalent)</h3>

<label style="text-align:left;">Name of the Board <span class="required">*</span></label>
<select name="board" required>
    <option value="">Select Board</option>

    <optgroup label="Central Boards">
        <option>CBSE</option>
        <option>CISCE (ISC)</option>
        <option>NIOS</option>
    </optgroup>

    <optgroup label="State Boards">
        <option>BIEAP</option>
        <option>TSBIE</option>
        <option>MSBSHSE</option>
        <option>TNBSE</option>
        <option>PUC / PUE</option>
        <option>DHSE</option>
        <option>UPMSP</option>
        <option>BSEB</option>
        <option>WBCHSE</option>
        <option>RBSE</option>
        <option>PSEB</option>
        <option>HBSE</option>
        <option>MPBSE</option>
        <option>CGBSE</option>
        <option>CHSE</option>
        <option>AHSEC</option>
        <option>JAC</option>
        <option>HPBOSE</option>
        <option>UBSE</option>
        <option>GBSHSE</option>
        <option>TBSE</option>
        <option>MBOSE</option>
        <option>COHSEM</option>
        <option>NBSE</option>
        <option>MBSE</option>
        <option>APBSE</option>
        <option>SBSE</option>
    </optgroup>
</select>

<label>Passing Year <span class="required">*</span></label>
<input type="month" name="passing_year" required>

<label>Total Marks(GRAND TOTAL) <span class="required">*</span></label>
<input type="number" name="total_marks" required>

<label>Group Marks(MPC) <span class="required">*</span></label>
<input type="number" name="group_marks" required>

<!-- NEW: Intermediate Details -->
<label style="text-align:left;">Intermediate Hall Ticket Number <span class="required">*</span></label>
<input type="text" name="intermediate_hall_ticket" required>

<label style="text-align:left;">Intermediate Percentage <span class="required">*</span></label>
<input type="number" step="0.01" name="inter_percentage" min="0" max="100" required>

<label style="text-align:left;">Intermediate Group Percentage (MPC) <span class="required">*</span></label>
<input type="number" step="0.01" name="inter_group_percentage" min="0" max="100" required>


<!-- PREFERENCES -->
<h3>Course Preferences</h3>

<select name="pref1" id="pref1" required onchange="checkPreferences()">
<option value="">Preference 1<span class="required">*</span></option>
<option value="ECE">ECE</option>
    <option value="EEE">EEE</option>
    <option value="ETM">ETM</option>
    <option value="CSM">CSM</option>
    <option value="CSD">CSD</option>
    <option value="IT">IT</option>
</select>

<select name="pref2" id="pref2" required onchange="checkPreferences()">
<option value="">Preference 2<span class="required">*</span></option>
<option value="ECE">ECE</option>
    <option value="EEE">EEE</option>
    <option value="ETM">ETM</option>
    <option value="CSM">CSM</option>
    <option value="CSD">CSD</option>
    <option value="IT">IT</option>
</select>

<select name="pref3" id="pref3" required onchange="checkPreferences()">
<option value="">Preference 3<span class="required">*</span></option>
<option value="CSE">CSE</option>
    <option value="ECE">ECE</option>
    <option value="EEE">EEE</option>
    <option value="ETM">ETM</option>
    <option value="CSM">CSM</option>
    <option value="CSD">CSD</option>
    <option value="IT">IT</option>
</select>

<select name="pref4" id="pref4" required onchange="checkPreferences()">
<option value="">Preference 4<span class="required">*</span></option>
<option value="CSE">CSE</option>
    <option value="ECE">ECE</option>
    <option value="EEE">EEE</option>
    <option value="ETM">ETM</option>
    <option value="CSM">CSM</option>
    <option value="CSD">CSD</option>
    <option value="IT">IT</option>
</select>

<select name="pref5" id="pref5" required onchange="checkPreferences()">
<option value="">Preference 5<span class="required">*</span></option>
<option value="CSE">CSE</option>
    <option value="ECE">ECE</option>
    <option value="EEE">EEE</option>
    <option value="ETM">ETM</option>
    <option value="CSM">CSM</option>
    <option value="CSD">CSD</option>
    <option value="IT">IT</option>
</select>
 

<label>Place / Country: <span class="required">*</span></label><br>
    <select name="place_country" required>
        <option value="">Select</option>
        <option value="India">India</option>
        <option value="USA">USA</option>
        <option value="UK">UK</option>
    </select>
 

<!-- FILES -->
<h3>Upload Documents</h3>

<label>Photo (Max 200KB) <span class="required">*</span></label>
<input type="file" name="photo" required accept="image/*" onchange="validateFile(this,200,'Photo')">

<label>10th Memo (PDF Max 300KB) <span class="required">*</span></label>
<input type="file" name="ssc" required accept="application/pdf" onchange="validateFile(this,300,'10th Memo')">

<label>Inter Memo (PDF Max 300KB) <span class="required">*</span></label>
<input type="file" name="inter" required accept="application/pdf" onchange="validateFile(this,300,'Inter memo')">

<label>Passport (PDF Max 300KB) <span class="required">*</span></label>
<input type="file" name="passport" required accept="application/pdf" onchange="validateFile(this,300,'Student Passport')">

<label>Address Proof (PDF Max 300KB) <span class="required">*</span></label>
<input type="file" name="address_proof" required accept="application/pdf" onchange="validateFile(this,300'Address proof')">

<label>Student Signature (Max 200KB) <span class="required">*</span></label>
<input type="file" name="stud_sign" required accept="image/*" onchange="validateFile(this,200,'student sign')">

<label>Parent Signature (Max 200KB) <span class="required">*</span></label>
<input type="file" name="parent_sign" required accept="image/*" onchange="validateFile(this,200,'parent sign')">
<br>
<hr>

<label>
<input type="checkbox" name="declaration" required>
DECLARATION: We hereby declare that all the information furnished above is true to the best of our knowledge. We are aware
and give you an undertaking that our application form can summarily be rejected if any information provided is
wrong
</label>

<br><br>

<input type="radio" name="fee" value="1" required> 1 INR
<p class="note">Pay Registration Fee through Payment Gateway to submit application.</p>

<div class="button-group">

    <!-- Preview Button -->
    <button type="submit" name="preview" formaction="preview_oci.php" class="preview-btn">
        Preview Before Pay
    </button>

    <!-- Clear Form Button -->
    <button type="reset" class="clear-btn">
        Clear Form
    </button>

    <!-- Final Submit Button -->
    
      
   <button type="submit" name="payment" formaction="oci_submit.php" class="submit-btn">
        Pay and Submit
    </button>

</form>

</div>

</body>
</html>