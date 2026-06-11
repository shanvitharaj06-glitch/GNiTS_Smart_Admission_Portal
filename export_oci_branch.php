<?php
require __DIR__ . '/vendor/autoload.php';
include "db/db_connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Create Excel
$spreadsheet = new Spreadsheet();

// Branches
$courses = ['ALL','CSE','ECE','EEE','ETM','CSM','IT','CSD'];

$sheetIndex = 0;

foreach($courses as $course){

    if($sheetIndex == 0){
        $sheet = $spreadsheet->getActiveSheet();
    } else {
        $sheet = $spreadsheet->createSheet();
    }

    $sheet->setTitle($course);

    // 🔥 HEADERS
    if($course == "ALL"){
        $headers = [
            'S.No','Date','OCI ID','Name','Father','Email','Phone Number','Board','Passing Year',
            'Total Marks','Pref1','Pref2','Pref3','Pref4','Pref5',
            'Country'
        ];
    } else {
        $headers = [
            'Course','Date','OCI ID','Name','Father','Email','Phone Number','Board',
            'Passing Year','Total Marks','Country'
        ];
    }

    // 🔵 HEADER DESIGN
    $col = 'A';
    foreach($headers as $h){
        $cell = $col.'1';
        $sheet->setCellValue($cell, $h);

        $sheet->getStyle($cell)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        $col++;
    }

    // 🔥 QUERY
    if($course == "ALL"){
        $query = "SELECT * FROM oci ORDER BY total_marks DESC";
    } else {
        $query = "SELECT * FROM oci WHERE 
            pref1='$course' OR 
            pref2='$course' OR 
            pref3='$course' OR 
            pref4='$course' OR 
            pref5='$course'
            ORDER BY total_marks DESC";
    }

    $result = $conn->query($query);

    $rowNum = 2;
    $sno = 1;

    while($row = $result->fetch_assoc()){

        if($course == "ALL"){

            // 🔥 FULL DATA
            $sheet->setCellValue('A'.$rowNum, $sno++);
            $sheet->setCellValue('B'.$rowNum, date("d-m-Y", strtotime($row['created_at'])));
            $sheet->setCellValue('C'.$rowNum, $row['reference_no']);
            $sheet->setCellValue('D'.$rowNum, $row['name']);
            $sheet->setCellValue('E'.$rowNum, $row['father']);
            $sheet->setCellValue('F'.$rowNum, $row['email']);
            $sheet->setCellValue('G'.$rowNum, $row['mobile']);
            $sheet->setCellValue('H'.$rowNum, $row['board']);
            $sheet->setCellValue('I'.$rowNum, $row['passing_year']);
            $sheet->setCellValue('J'.$rowNum, $row['total_marks']);

            $sheet->setCellValue('K'.$rowNum, $row['pref1']);
            $sheet->setCellValue('L'.$rowNum, $row['pref2']);
            $sheet->setCellValue('M'.$rowNum, $row['pref3']);
            $sheet->setCellValue('N'.$rowNum, $row['pref4']);
            $sheet->setCellValue('O'.$rowNum, $row['pref5']);

            $sheet->setCellValue('P'.$rowNum, $row['place_country']);
            

        } else {

            // 🔥 BRANCH SHEET
            $sheet->setCellValue('A'.$rowNum, $course);
            $sheet->setCellValue('B'.$rowNum, date("d-m-Y", strtotime($row['created_at'])));
            $sheet->setCellValue('C'.$rowNum, $row['reference_no']);
            $sheet->setCellValue('D'.$rowNum, $row['name']);
            $sheet->setCellValue('E'.$rowNum, $row['father']);
             $sheet->setCellValue('F'.$rowNum, $row['email']);
            $sheet->setCellValue('G'.$rowNum, $row['mobile']);
            $sheet->setCellValue('H'.$rowNum, $row['board']);
            $sheet->setCellValue('I'.$rowNum, $row['passing_year']);
            $sheet->setCellValue('J'.$rowNum, $row['total_marks']);
            $sheet->setCellValue('K'.$rowNum, $row['place_country']);
            
        }

        // 🧾 ROW BORDER
        $lastCol = ($course == "ALL") ? 'N' : 'I';
        $sheet->getStyle('A'.$rowNum.':'.$lastCol.$rowNum)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        $rowNum++;
    }

    // 🔥 AUTO SIZE
    foreach(range('A','N') as $colID){
        $sheet->getColumnDimension($colID)->setAutoSize(true);
    }

    $sheetIndex++;
}

// Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="OCI_Branch_Merit.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;
?>