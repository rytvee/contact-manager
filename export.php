<?php
session_start();
include 'db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Disable output buffering and clear any stray spaces/newlines
if (ob_get_length()) ob_end_clean();

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $sql = "SELECT name, phone, email FROM contacts";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        $_SESSION['export_error'] = "No contacts found to export.";
        header("Location: index.php");
        exit;
    }

    // ==============================
    // Export as CSV
    // ==============================
    if ($type === "csv") {
        $_SESSION['msg_success'] = "Contacts exported successfully as CSV file.";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="contacts.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Ensure no empty first line
        fputcsv($output, ["Name", "Phone", "Email"]);

        while ($row = $result->fetch_assoc()) {
            // Force phone numbers to text for leading zeroes
            $phone = "=\"" . $row['phone'] . "\"";
            fputcsv($output, [$row['name'], $phone, $row['email']]);
        }

        fclose($output);
        exit;
    }

    // ==============================
    // Export as Excel (.xlsx)
    // ==============================
    if ($type === "excel") {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Contacts');

        // Header
        $sheet->fromArray(["Name", "Phone", "Email"], null, 'A1');

        // Data rows
        $rowNum = 2;
        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowNum, $row['name']);
            // Force phone to text
            $sheet->setCellValueExplicit('B' . $rowNum, $row['phone'], DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowNum, $row['email']);
            $rowNum++;
        }

        // Auto-size columns
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Clean output buffers
        if (ob_get_length()) ob_end_clean();

        $_SESSION['msg_success'] = "Contacts exported successfully as Excel file.";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="contacts.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    $_SESSION['export_error'] = "Invalid export type.";
    header("Location: index.php");
    exit;
}
?>