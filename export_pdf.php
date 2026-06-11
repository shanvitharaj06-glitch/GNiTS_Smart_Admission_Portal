<?php

require('lib/FPDF-master/fpdf.php');
include "db/db_connect.php";

$type = $_GET['type'] ?? 'jee';

if($type == "nri"){
    $table = "nri";
} elseif($type == "oci"){
    $table = "oci";
} else {
    $table = "jee";
}

$result = $conn->query("SELECT * FROM $table");

class PDF extends FPDF {
    function Header(){
        $this->Image('pics/logo.jpg',10,8,20);
        $this->Cell(30);
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'G. NARAYANAMMA INSTITUTE OF TECHNOLOGY AND SCIENCE',0,1,'C');
        $this->SetFont('Arial','',10);
        $this->Cell(0,8,'Student Admission Report',0,1,'C');
        $this->Ln(3);
        $this->SetDrawColor(50,50,150);
        $this->SetLineWidth(0.5);
        $this->Line(10,30,200,30);
        $this->SetLineWidth(0.2);
        $this->SetDrawColor(0,0,0);
        $this->Ln(2);
    }

    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(120,120,120);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        $this->SetTextColor(0,0,0);
    }

    function SectionTitle($title){
        $this->Ln(4);
        $this->SetFont('Arial','B',11);
        $this->SetTextColor(255,255,255);
        $this->SetFillColor(50,50,140);
        $this->Cell(130,8,'  '.$title,0,1,'L',true);
        $this->SetTextColor(0,0,0);
        $this->Ln(2);
    }

    function LabelValue($label, $value){
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(80,80,80);
        $this->Cell(50,7,$label.' :',0,0,'R');
        $this->SetFont('Arial','',10);
        $this->SetTextColor(30,30,30);
        $this->Cell(5);
        $this->Cell(80,7,$value,0,1,'L');
    }

    function FileStatus($label, $status){
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(80,80,80);
        $this->Cell(50,7,$label.' :',0,0,'R');
        $this->Cell(5);
        if($status == "YES"){
            $this->SetTextColor(0,140,0);
            $this->SetFont('Arial','B',10);
            $this->Cell(80,7,'Uploaded',0,1,'L');
        } else {
            $this->SetTextColor(200,0,0);
            $this->SetFont('Arial','B',10);
            $this->Cell(80,7,'Not Uploaded',0,1,'L');
        }
        $this->SetTextColor(0,0,0);
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,20);

// APPROVED JEE LIST
if(isset($_GET['type']) && $_GET['type'] == "approved_jee"){
    $result2 = $conn->query("SELECT reference_no, name, jee_rank, email FROM jee WHERE status='Approved' ORDER BY jee_rank ASC");
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,'Approved JEE Students List',0,1,'C');
    $pdf->Ln(3);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetFillColor(50,50,140);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(30,10,'Ref No',1,0,'C',true);
    $pdf->Cell(50,10,'Name',1,0,'C',true);
    $pdf->Cell(25,10,'Rank',1,0,'C',true);
    $pdf->Cell(55,10,'Email',1,1,'C',true);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','',10);
    $fill = false;
    while($row2 = $result2->fetch_assoc()){
        $pdf->SetFillColor(240,240,255);
        $pdf->Cell(30,10,$row2['reference_no'],1,0,'C',$fill);
        $pdf->Cell(50,10,$row2['name'],1,0,'L',$fill);
        $pdf->Cell(25,10,$row2['jee_rank'],1,0,'C',$fill);
        $pdf->Cell(55,10,$row2['email'],1,1,'L',$fill);
        $fill = !$fill;
    }
    $pdf->Output("D","approved_jee_students.pdf");
    exit();
}

// APPROVED NRI LIST
if(isset($_GET['type']) && $_GET['type'] == "approved_nri"){
    $result2 = $conn->query("SELECT reference_no, name, eamcet_rank, email FROM nri WHERE status='Approved' ORDER BY eamcet_rank ASC");
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,'Approved NRI Students List',0,1,'C');
    $pdf->Ln(3);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetFillColor(50,50,140);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(30,10,'Ref No',1,0,'C',true);
    $pdf->Cell(50,10,'Name',1,0,'C',true);
    $pdf->Cell(30,10,'Rank',1,0,'C',true);
    $pdf->Cell(55,10,'Email',1,1,'C',true);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','',10);
    $fill = false;
    while($row2 = $result2->fetch_assoc()){
        $pdf->SetFillColor(240,240,255);
        $pdf->Cell(30,10,$row2['reference_no'],1,0,'C',$fill);
        $pdf->Cell(50,10,$row2['name'],1,0,'L',$fill);
        $pdf->Cell(30,10,$row2['eamcet_rank'],1,0,'C',$fill);
        $pdf->Cell(55,10,$row2['email'],1,1,'L',$fill);
        $fill = !$fill;
    }
    $pdf->Output("D","approved_nri_students.pdf");
    exit();
}

// APPROVED OCI LIST
if(isset($_GET['type']) && $_GET['type'] == "approved_oci"){
    $result2 = $conn->query("SELECT reference_no, name, inter_percentage, email FROM oci ORDER BY inter_percentage DESC");
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,'OCI Students List',0,1,'C');
    $pdf->Ln(3);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetFillColor(50,50,140);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(30,10,'Ref No',1,0,'C',true);
    $pdf->Cell(55,10,'Name',1,0,'C',true);
    $pdf->Cell(30,10,'Inter %',1,0,'C',true);
    $pdf->Cell(55,10,'Email',1,1,'C',true);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','',10);
    $fill = false;
    while($row2 = $result2->fetch_assoc()){
        $pdf->SetFillColor(240,240,255);
        $pdf->Cell(30,10,$row2['reference_no'],1,0,'C',$fill);
        $pdf->Cell(55,10,$row2['name'],1,0,'L',$fill);
        $pdf->Cell(30,10,$row2['inter_percentage'],1,0,'C',$fill);
        $pdf->Cell(55,10,$row2['email'],1,1,'L',$fill);
        $fill = !$fill;
    }
    $pdf->Output("D","oci_students.pdf");
    exit();
}

function checkFile($file){
    return (!empty($file)) ? "YES" : "NO";
}

// LOOP ALL STUDENTS
while($row = $result->fetch_assoc()){
    $pdf->AddPage();

    // Student name heading
    $pdf->SetFont('Arial','B',15);
    $pdf->SetTextColor(50,50,140);
    $pdf->Cell(0,10,$row['name'],0,1,'L');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetDrawColor(50,50,140);
    $pdf->SetLineWidth(0.4);
    $pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
    $pdf->SetLineWidth(0.2);
    $pdf->SetDrawColor(0,0,0);
    $pdf->Ln(2);

    // Photo on right side
    $photoY = $pdf->GetY();
    if(!empty($row['photo']) && file_exists("uploads/".$row['photo'])){
        $pdf->Image("uploads/".$row['photo'], 155, $photoY, 35, 40);
    }

    // ===== PERSONAL DETAILS =====
    $pdf->SectionTitle('Personal Details');
    $pdf->LabelValue('Reference No', $row['reference_no']);
    $pdf->LabelValue('Name', $row['name']);
    $pdf->LabelValue('Gender', $row['gender']);
    $pdf->LabelValue('Date of Birth', $row['dob']);
    $pdf->LabelValue('Father\'s Name', $row['father']);
    $pdf->LabelValue('Mother\'s Name', $row['mother']);
    $pdf->LabelValue('Community', $row['community']);

    // ===== ADDRESS DETAILS =====
    $pdf->SectionTitle('Address Details');
    $pdf->LabelValue('Address Line 1', $row['address1']);
    $pdf->LabelValue('Address Line 2', $row['address2']);
    $pdf->LabelValue('City', $row['city']);
    $pdf->LabelValue('State', $row['state']);
    $pdf->LabelValue('ZIP Code', $row['zip']);
    $pdf->LabelValue('Country', $row['country']);

    // ===== CONTACT DETAILS =====
    $pdf->SectionTitle('Contact Details');
    $pdf->LabelValue('Email', $row['email']);
    $pdf->LabelValue('Aadhar No', $row['aadhar']);
    $pdf->LabelValue('Mobile', $row['mobile']);

    // ===== ACADEMIC DETAILS =====
    $pdf->SectionTitle('Academic Details');
    $pdf->LabelValue('Board', $row['board']);
    $pdf->LabelValue('Passing Year', $row['passing_year']);
    $pdf->LabelValue('Total Marks', $row['total_marks']);
    $pdf->LabelValue('Group Marks', $row['group_marks']);
    $pdf->LabelValue('Inter Hall Ticket', $row['intermediate_hall_ticket']);
    $pdf->LabelValue('Inter Percentage', $row['inter_percentage'].'%');
    $pdf->LabelValue('Inter Group %', $row['inter_group_percentage'].'%');

    // ===== TYPE-SPECIFIC SECTIONS =====

    if($type == "jee"){
        $pdf->SectionTitle('Entrance Details');
        $pdf->LabelValue('JEE Hall Ticket', $row['jee_hall_ticket']);
        $pdf->LabelValue('JEE Rank', $row['jee_rank']);
        $pdf->LabelValue('JEE Percentile', $row['jee_percentile'].'%');
        $pdf->LabelValue('EAMCET Hall Ticket', $row['eamcet_hall_ticket']);
        $pdf->LabelValue('EAMCET Rank', $row['eamcet_rank']);
    }

    if($type == "nri"){
        $pdf->SectionTitle('NRI Sponsor Details');
        $pdf->LabelValue('Relationship', $row['relationship']);
        $pdf->LabelValue('Sponsor Name', $row['sponsor_name']);
        $pdf->LabelValue('Place / Country', $row['place_country']);
        $pdf->LabelValue('EAMCET Rank', $row['eamcet_rank']);
    }

    if($type == "oci"){
        $pdf->SectionTitle('OCI Details');
        $pdf->LabelValue('Place / Country', $row['place_country']);
    }

    // ===== PREFERENCES =====
    $pdf->SectionTitle('Branch Preferences');
    $pdf->LabelValue('Preference 1', $row['pref1']);
    $pdf->LabelValue('Preference 2', $row['pref2']);
    $pdf->LabelValue('Preference 3', $row['pref3']);
    $pdf->LabelValue('Preference 4', $row['pref4']);
    $pdf->LabelValue('Preference 5', $row['pref5']);

    // ===== UPLOADED DOCUMENTS =====
    if($type == "jee"){
        $files = [
            'Photo'=>$row['photo'],
            '10th Marksheet'=>$row['tenth'],
            'Inter Marksheet'=>$row['inter'],
            'JEE Card'=>$row['jee_card'],
            'EAMCET Card'=>$row['eamcet_card'],
            'Student Sign'=>$row['stud_sign'],
            'Parent Sign'=>$row['parent_sign']
        ];
    } elseif($type == "nri"){
        $files = [
            'Photo'=>$row['photo'],
            '10th Marksheet'=>$row['tenth'],
            'Inter Marksheet'=>$row['inter'],
            'Passport'=>$row['passport'],
            'EAMCET Card'=>$row['eamcet_card'],
            'NRI Letter'=>$row['nri_letter'],
            'NRI Driving License'=>$row['nri_driving'],
            'Student Sign'=>$row['stud_sign'],
            'Parent Sign'=>$row['parent_sign']
        ];
    } elseif($type == "oci"){
        $files = [
            'Photo'=>$row['photo'],
            'SSC Certificate'=>$row['ssc'],
            'Inter Marksheet'=>$row['inter'],
            'Passport'=>$row['passport'],
            'Address Proof'=>$row['address_proof'],
            'Student Sign'=>$row['stud_sign'],
            'Parent Sign'=>$row['parent_sign']
        ];
    }

    $pdf->SectionTitle('Uploaded Documents');
    $total = 0;
    $uploaded = 0;
    foreach($files as $label=>$file){
        $status = checkFile($file);
        $pdf->FileStatus($label, $status);
        $total++;
        if($status == "YES") $uploaded++;
    }

    $pdf->Ln(3);
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(80,80,80);
    $pdf->Cell(50,7,'Total Uploaded :',0,0,'R');
    $pdf->Cell(5);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetTextColor(50,50,140);
    $pdf->Cell(80,7,"$uploaded / $total",0,1,'L');
    $pdf->SetTextColor(0,0,0);
}

$pdf->Output("D", $type."_professional_report.pdf");
?>
