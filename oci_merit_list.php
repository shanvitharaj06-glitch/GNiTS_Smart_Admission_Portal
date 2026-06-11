<?php
include "db/db_connect.php";

// Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=OCI_Merit_List.xls");

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
     <th>OCI ID</th>
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
    <th>Country</th>
   
</tr>";

$sno = 1;

// 🔥 MERIT LOGIC → Highest marks first
$query = $conn->query("
    SELECT * FROM oci 
    ORDER BY total_marks DESC
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
        <td>{$row['place_country']}</td>
       
    </tr>";

    $sno++;
}

echo "</table>";
exit;
?>