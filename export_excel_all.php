<?php
include "db/db_connect.php";

// Force Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Annexure - II - List of Applied for Admissions into BTech for the academic year.xls");

// Start table
echo "<table border='1'>";

// HEADER ROW (with color)
echo "<tr style='background-color:#4CAF50;color:white;font-weight:bold;'>
    <th>S.No</th>
    <th>Date</th>
     <th>Application ID</th>
    <th>Name</th>
    <th>Father</th>
        <th>Email</th>
            <th>Phone Number</th>
    <th>Inter Marks</th>
    <th>JEE Rank</th>
    <th>EAMCET Rank</th>
    <th>Fee</th>
    <th>Transaction ID</th>
    <th>Bank</th>
</tr>";

$sno = 1;

// ---------- JEE ----------
$q1 = $conn->query("SELECT * FROM jee ORDER BY created_at ASC");

while($row = $q1->fetch_assoc()){
    echo "<tr>
        <td>$sno</td>
        <td>".date("d-m-Y", strtotime($row['created_at']))."</td>
        <td>{$row['reference_no']}</td>
        <td>{$row['name']}</td>
        <td>{$row['father']}</td>
        <td>{$row['email']}</td>
        <td>{$row['mobile']}</td>
        <td>{$row['total_marks']}</td>
        <td>{$row['jee_rank']}</td>
        <td>{$row['eamcet_rank']}</td>
        <td>{$row['payment_amount']}</td>
        <td>{$row['txnid']}</td>
        <td>Pay U Payment GateWay</td>
         
    </tr>";
    $sno++;
}

// ---------- NRI ----------
$q2 = $conn->query("SELECT * FROM nri ORDER BY created_at ASC");

while($row = $q2->fetch_assoc()){
    echo "<tr style='background-color:#f2f2f2;'>
        <td>$sno</td>
        <td>".date("d-m-Y", strtotime($row['created_at']))."</td>
        <td>{$row['reference_no']}</td>
        <td>{$row['name']}</td>
        <td>{$row['father']}</td>
         <td>{$row['email']}</td>
        <td>{$row['mobile']}</td>
        <td>{$row['total_marks']}</td>
        <td>-</td>
        <td>{$row['eamcet_rank']}</td>
        
        <td>{$row['payment_amount']}</td>
        <td>{$row['txnid']}</td>
        <td>Pay U Payment GateWay</td>
         
    </tr>";
    $sno++;
}

// ---------- OCI ----------
$q3 = $conn->query("SELECT * FROM oci ORDER BY created_at ASC");

while($row = $q3->fetch_assoc()){
    echo "<tr style='background-color:#e6f7ff;'>
        <td>$sno</td>
        <td>".date("d-m-Y", strtotime($row['created_at']))."</td>
        <td>{$row['reference_no']}</td>
        <td>{$row['name']}</td>
        <td>{$row['father']}</td>
         <td>{$row['email']}</td>
        <td>{$row['mobile']}</td>
        <td>{$row['total_marks']}</td>
        <td>-</td>
        <td>-</td>
        
        <td>{$row['payment_amount']}</td>
        <td>{$row['txnid']}</td>
        <td>Pay U Payment GateWay</td>
         
    </tr>";
    $sno++;
}

echo "</table>";
exit;
?>