<?php
require 'vendor/autoload.php'; // Load PhpSpreadsheet
include 'includes/db.php'; // Correct path to db.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Query to get all student payments
$sql = "SELECT * FROM payments";
$result = $conn->query($sql);

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the column headers
$sheet->setCellValue('A1', 'Payment ID');
$sheet->setCellValue('B1', 'Student ID');
$sheet->setCellValue('C1', 'Name of Student');
$sheet->setCellValue('D1', 'Branch');
$sheet->setCellValue('E1', 'Year');
$sheet->setCellValue('F1', 'Particulars of Fee');
$sheet->setCellValue('G1', 'Amount');
$sheet->setCellValue('H1', 'Payment Method');
$sheet->setCellValue('I1', 'Payment Date');

// Fetch data and write it to the spreadsheet
$rowNumber = 2; // Start from the second row
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['payment_id']);
        $sheet->setCellValue('B' . $rowNumber, $row['student_id']);
        $sheet->setCellValue('C' . $rowNumber, $row['name_of_student']);
        $sheet->setCellValue('D' . $rowNumber, $row['branch']);
        $sheet->setCellValue('E' . $rowNumber, $row['year']);
        $sheet->setCellValue('F' . $rowNumber, $row['particulars_fee']);
        $sheet->setCellValue('G' . $rowNumber, $row['amount']);
        $sheet->setCellValue('H' . $rowNumber, $row['payment_method']);
        $sheet->setCellValue('I' . $rowNumber, $row['payment_date']);
        $rowNumber++;
    }
}

// Generate Excel file and force download
$writer = new Xlsx($spreadsheet);

// Set the headers for file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="student_payments.xlsx"');
header('Cache-Control: max-age=0');

// Output the file to the browser for download
$writer->save('php://output');
exit();
?>
