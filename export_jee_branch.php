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

// Courses
$courses = ['ALL','CSE','ECE','EEE','ETM','CSM','IT','CSD'];

$sheetIndex = 0;

foreach($courses as $course){

    if($sheetIndex == 0){
        $sheet = $spreadsheet->getActiveSheet();
    } else {
        $sheet = $spreadsheet->createSheet();
    }

    $sheet->setTitle($course);

    // ✅ HEADERS
    if($course == "ALL"){
        $headers = [
            'S.No','Date','JEE ID','Name','Father','Email','Phone Number','Board',
            'Passing Year','Total Marks',
            'Pref1','Pref2','Pref3','Pref4','Pref5',
            'JEE Rank','JEE Percentile','EAMCET Rank'
        ];
    } else {
        $headers = [
            'S.No','Course','Date','JEE ID','Name','Father','Email','Phone Number','Board',
            'Passing Year','Total Marks',
            'JEE Rank','JEE Percentile','EAMCET Rank'
        ];
    }

    // ✅ APPLY HEADER STYLE
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
                'startColor' => ['rgb' => '4CAF50'] // Green
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        $col++;
    }

    // ✅ QUERY
    if($course == "ALL"){
        $query = "SELECT * FROM jee 
                  ORDER BY total_marks DESC, jee_rank ASC";
    } else {
        $query = "SELECT * FROM jee WHERE 
            pref1='$course' OR 
            pref2='$course' OR 
            pref3='$course' OR 
            pref4='$course' OR 
            pref5='$course'
            ORDER BY total_marks DESC, jee_rank ASC";
    }

    $result = $conn->query($query);

    $rowNum = 2;
    $sno = 1;

    while($row = $result->fetch_assoc()){

        if($course == "ALL"){

            // ✅ FULL DATA
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

            $sheet->setCellValue('P'.$rowNum, $row['jee_rank']);
            $sheet->setCellValue('Q'.$rowNum, $row['jee_percentile']);
            $sheet->setCellValue('R'.$rowNum, $row['eamcet_rank']);
            

        } else {

            // ✅ BRANCH DATA
            $sheet->setCellValue('A'.$rowNum, $sno++);
            $sheet->setCellValue('B'.$rowNum, $course);
            $sheet->setCellValue('C'.$rowNum, date("d-m-Y", strtotime($row['created_at'])));
             $sheet->setCellValue('D'.$rowNum, $row['reference_no']);
            $sheet->setCellValue('E'.$rowNum, $row['name']);
            $sheet->setCellValue('F'.$rowNum, $row['father']);
             $sheet->setCellValue('G'.$rowNum, $row['email']);
                $sheet->setCellValue('H'.$rowNum, $row['mobile']);
            $sheet->setCellValue('I'.$rowNum, $row['board']);
            $sheet->setCellValue('J'.$rowNum, $row['passing_year']);
            $sheet->setCellValue('K'.$rowNum, $row['total_marks']);
            $sheet->setCellValue('L'.$rowNum, $row['jee_rank']);
            $sheet->setCellValue('M'.$rowNum, $row['jee_percentile']);
            $sheet->setCellValue('N'.$rowNum, $row['eamcet_rank']);
        }

        // ✅ ROW BORDER STYLE
        $sheet->getStyle('A'.$rowNum.':'.$col.$rowNum)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        $rowNum++;
    }

    // ✅ AUTO WIDTH
    foreach(range('A','Z') as $columnID){
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheetIndex++;
}

// ✅ DOWNLOAD
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="JEE_All_Branches.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;
?>