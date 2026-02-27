
<?php
session_start();
require 'vendor/autoload.php';
include "db.php";

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['size'] > 0) {
        $fileName = $_FILES['file']['tmp_name'];
        $fileType = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        try {
            // Load spreadsheet (works for CSV, XLS, XLSX)
            $reader = IOFactory::createReaderForFile($fileName);
            $spreadsheet = $reader->load($fileName);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $imported = 0;
            $skipped = 0;

            // Skip header row (start from 2nd)
            for ($i = 1; $i < count($rows); $i++) {
                $name  = trim($rows[$i][0]);
                $phone = trim($rows[$i][1]);
                $email = trim($rows[$i][2]);

                // Skip if any field missing
                if (empty($name) || empty($phone) || empty($email)) {
                    $skipped++;
                    continue;
                }

                // Check for duplicates by name or email
                $check = $conn->prepare("SELECT id FROM contacts WHERE name=? OR email=? LIMIT 1");
                $check->bind_param("ss", $name, $email);
                $check->execute();
                $check->store_result();

                if ($check->num_rows === 0) {
                    $stmt = $conn->prepare("INSERT INTO contacts (name, phone, email) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $name, $phone, $email);
                    $stmt->execute();
                    $imported++;
                } else {
                    $skipped++;
                }

                $check->close();
            }

            $_SESSION['msg_success'] = "Imported {$imported} contact(s). Skipped {$skipped} invalid or duplicate record(s).";
        } catch (Exception $e) {
            $_SESSION['import_error'] = "Error reading file: " . $e->getMessage();
        }
    } else {
        $_SESSION['import_error'] = "Please upload a valid Excel or CSV file.";
    }
} else {
    $_SESSION['import_error'] = "No file uploaded.";
}

header("Location: index.php");
exit;
?>
