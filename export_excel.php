<?php
include "db/db_connect.php";

$type = $_GET['type'] ?? 'jee';
$table = ($type == "nri") ? "nri" : "jee";

$result = $conn->query("SELECT * FROM $table");

// 🔷 BASE URL
$base_url = "http://gnitscollege.in/uploads/";

// 🔷 HEADERS
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$type."_complete_details.xls");

echo "<table border='1'>";

// 🔷 HEADERS ROW
echo "<tr>

<th>Reference No</th>

<!-- PERSONAL -->
<th>Name</th>
<th>DOB</th>
<th>Father</th>
<th>Mother</th>

<!-- ADDRESS -->
<th>Address 1</th>
<th>Address 2</th>
<th>City</th>
<th>State</th>
<th>ZIP</th>
<th>Country</th>

<!-- CONTACT -->
<th>Email</th>
<th>Confirm Email</th>
<th>Aadhar</th>
<th>Mobile</th>

<!-- ACADEMIC -->
<th>Board</th>
<th>Passing Year</th>
<th>Total Marks</th>
<th>Group Marks</th>

<!-- ENTRANCE -->
";

// 🔷 TYPE BASED
if($type == "jee"){
    echo "
    <th>JEE Rank</th>
    <th>JEE Percentile</th>
    <th>EAMCET Rank</th>";
} else {
    echo "<th>EAMCET Rank</th>";
}

// 🔷 PREFERENCES
echo "
<th>Pref1</th>
<th>Pref2</th>
<th>Pref3</th>
<th>Pref4</th>
<th>Pref5</th>
";

// 🔷 FILE HEADERS
if($type == "jee"){
    echo "
    <th>Photo</th>
    <th>10th</th>
    <th>Inter</th>
    <th>JEE Card</th>
    <th>EAMCET Card</th>
    <th>Student Sign</th>
    <th>Parent Sign</th>";
} else {
    echo "
    <th>Photo</th>
    <th>10th</th>
    <th>Inter</th>
    <th>Passport</th>
    <th>EAMCET Card</th>
    <th>NRI Letter</th>
    <th>NRI Driving</th>
    <th>Student Sign</th>
    <th>Parent Sign</th>";
}

echo "</tr>";


// 🔷 DOWNLOAD LINK FUNCTION
function downloadLink($file, $base_url){
    if(!empty($file)){
        return "<a href='".$base_url.$file."'>Download</a>";
    } else {
        return "No File";
    }
}


// 🔷 DATA ROWS
while($row = $result->fetch_assoc()){

    echo "<tr>";

    echo "<td>{$row['reference_no']}</td>";

    // PERSONAL
    echo "<td>{$row['name']}</td>";
    echo "<td>{$row['dob']}</td>";
    echo "<td>{$row['father']}</td>";
    echo "<td>{$row['mother']}</td>";

    // ADDRESS
    echo "<td>{$row['address1']}</td>";
    echo "<td>{$row['address2']}</td>";
    echo "<td>{$row['city']}</td>";
    echo "<td>{$row['state']}</td>";
    echo "<td>{$row['zip']}</td>";
    echo "<td>{$row['country']}</td>";

    // CONTACT
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['confirm_email']}</td>";
    echo "<td>{$row['aadhar']}</td>";
    echo "<td>{$row['mobile']}</td>";

    // ACADEMIC
    echo "<td>{$row['board']}</td>";
    echo "<td>{$row['passing_year']}</td>";
    echo "<td>{$row['total_marks']}</td>";
    echo "<td>{$row['group_marks']}</td>";

    // ENTRANCE
    if($type == "jee"){
        echo "<td>{$row['jee_rank']}</td>";
        echo "<td>{$row['jee_percentile']}</td>";
        echo "<td>{$row['eamcet_rank']}</td>";
    } else {
        echo "<td>{$row['eamcet_rank']}</td>";
    }

    // PREFERENCES
    echo "<td>{$row['pref1']}</td>";
    echo "<td>{$row['pref2']}</td>";
    echo "<td>{$row['pref3']}</td>";
    echo "<td>{$row['pref4']}</td>";
    echo "<td>{$row['pref5']}</td>";

    // FILE LINKS
    if($type == "jee"){
        $files = [
            $row['photo'],
            $row['tenth'],
            $row['inter'],
            $row['jee_card'],
            $row['eamcet_card'],
            $row['stud_sign'],
            $row['parent_sign']
        ];
    } else {
        $files = [
            $row['photo'],
            $row['tenth'],
            $row['inter'],
            $row['passport'],
            $row['eamcet_card'],
            $row['nri_letter'],
            $row['nri_driving'],
            $row['stud_sign'],
            $row['parent_sign']
        ];
    }

    foreach($files as $file){
        echo "<td>".downloadLink($file, $base_url)."</td>";
    }

    echo "</tr>";
}

echo "</table>";
?>