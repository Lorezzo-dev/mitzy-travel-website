<?php
// ==============================================
// UNIVERSAL VISA APPLICATION LOGGER
// Logs applicant name, email, and passport number
// into /logs/visa_applications_log_YYYY-MM-DD.csv
// Works for Schengen, Korea, Japan, etc.
// ==============================================

// -------------------------------
// CONFIG
// -------------------------------
$enableVisaLog = true;
$logDir = __DIR__ . "/../logs";

// Ensure log folder exists
if ($enableVisaLog && !is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// -------------------------------
// AUTO-DETECT COMMON FIELDS
// -------------------------------

// Name variations
$given = $_POST['given_name'] ?? $_POST['givennames'] ?? $_POST['givenNames'] ?? '';
$surname = $_POST['surname'] ?? $_POST['familyname'] ?? $_POST['family_name'] ?? '';
$fullName = trim("$given $surname");

// Email variations
$email = $_POST['personal_email'] ?? $_POST['email'] ?? $_POST['company_email'] ?? '';

// Passport number variations
$passport = $_POST['passport_number'] ?? $_POST['passportNumber'] ?? $_POST['passportnumber'] ?? '';

// Visa type
$visaType = $_POST['visa_type'] ?? 'Visa Application';

// Page info
$formFile = basename($_SERVER['SCRIPT_FILENAME']);
$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$timestamp = date("Y-m-d H:i:s");

// -------------------------------
// WRITE TO CSV
// -------------------------------
if ($enableVisaLog) {
    $csvFile = $logDir . "/visa_applications_log_" . date("Y-m-d") . ".csv";
    $isNew = !file_exists($csvFile);
    $csv = fopen($csvFile, "a");

    if ($isNew) {
        fputcsv($csv, ["Timestamp", "Form File", "Applicant Name", "Email", "Passport Number", "Visa Type", "IP Address"]);
    }

    fputcsv($csv, [
        $timestamp,
        $formFile,
        $fullName ?: "N/A",
        $email ?: "N/A",
        $passport ?: "N/A",
        $visaType,
        $ip
    ]);

    fclose($csv);
}
?>