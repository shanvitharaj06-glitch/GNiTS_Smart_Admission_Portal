<?php
include "db/db_connect.php";

// Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=JEE_Merit_List.xls");

// Styling
echo "<style>
table { border-collapse: collapse; }
td, th { padding:8px; }
</style>";

// Table start
echo "<table border='1'>";

// HEADER
echo "<tr style='background-color:#4CAF50;color:white;font-weight:bold;'>
    <th>S.No</th>
    <th>Date</th>
     <th>JEE ID</th>
    <th>Name of Applicant</th>
    <th>Father's Name</th>
    <th>Email</th>
    <th>Phone Number</th>
    <th>Board</th>
    <th>Passing Year</th>
    <th>Total Marks</th>
    <th>Preference 1</th>
    <th>Preference 2</th>
    <th>Preference 3</th>
    <th>Preference 4</th>
    <th>Preference 5</th>
    <th>JEE Rank</th>
    <th>JEE Percentile</th>
    <th>EAMCET Rank</th>
   
</tr>";

$sno = 1;

// 🔥 MERIT LOGIC → Marks DESC, Rank ASC
$query = $conn->query("
    SELECT * FROM jee 
    ORDER BY total_marks DESC, jee_rank ASC
");

while($row = $query->fetch_assoc()){

    echo "<tr style='background-color:#f9f9f9;'>
        <td>$sno</td>
        <td>".date("d-m-Y", strtotime($row['created_at']))."</td>
        <td>{$row['reference_no']}</td>
        <td><b>{$row['name']}</b></td>
        <td>{$row['father']}</td>
        <td>{$row['email']}</td>
        <td>{$row['mobile']}</td>
        <td>{$row['board']}</td>
        <td>{$row['passing_year']}</td>
        <td><b>{$row['total_marks']}</b></td>
        <td>{$row['pref1']}</td>
        <td>{$row['pref2']}</td>
        <td>{$row['pref3']}</td>
        <td>{$row['pref4']}</td>
        <td>{$row['pref5']}</td>
        <td>{$row['jee_rank']}</td>
        <td>{$row['jee_percentile']}</td>
        <td>{$row['eamcet_rank']}</td>
        
    </tr>";

    $sno++;
}

echo "</table>";
exit;
?>