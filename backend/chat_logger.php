<?php
// ==============================================
// UNIVERSAL CHAT / INQUIRY / APPLICATION LOGGER
// Logs all form submissions into /logs/chat_log_YYYY-MM-DD.csv
// Works even if 'message' field is missing (e.g. application forms)
// ==============================================

// -------------------------------
// CONFIGURATION
// -------------------------------
$enableStats = true; // set to false to disable logging
$logDir = __DIR__ . "/../logs"; // folder for logs
$spamWords = [
    'crypto', 'commission', 'affiliate', 'nft', 'investment', 'trading',
    'forex', 'earn money', 'bitcoin', 'casino', 'seo service', 'guest post'
];

// -------------------------------
// CREATE LOG FOLDER IF MISSING
// -------------------------------
if ($enableStats && !is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// -------------------------------
// COLLECT FORM DATA (safe & flexible)
// -------------------------------
$name    = trim($_POST['name']    ?? $_POST['fullname'] ?? '');
$email   = trim($_POST['email']   ?? '');
$message = trim($_POST['message'] ?? $_POST['inquiry'] ?? ''); // fallback empty if not present
$formType = basename($_SERVER['SCRIPT_FILENAME']); // which script triggered this

// -------------------------------
// BASIC SPAM FILTER (only if message exists)
// -------------------------------
if (!empty($message)) {
    $checkMessage = strtolower($message);
    foreach ($spamWords as $word) {
        if (strpos($checkMessage, $word) !== false) {
            die("❌ Spam detected. Submission blocked.");
        }
    }
}

// -------------------------------
// LOG STATS (if enabled)
// -------------------------------
if ($enableStats) {
    $timestamp = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $page = $_SERVER['REQUEST_URI'] ?? 'Unknown';

    // Create daily log file
    $csvFile = $logDir . "/chat_log_" . date("Y-m-d") . ".csv";
    $isNewFile = !file_exists($csvFile);

    // Prepare row with fallback text
    $row = [
        $timestamp,
        $formType,
        $name ?: 'N/A',
        $email ?: 'N/A',
        $message ?: '[No message field]',
        $ip,
        $userAgent,
        $page
    ];

    $csv = fopen($csvFile, "a");

    if ($isNewFile) {
        fputcsv($csv, [
            "Timestamp", "Form File", "Name", "Email", "Message",
            "IP Address", "User Agent", "Page"
        ]);
    }

    fputcsv($csv, $row);
    fclose($csv);
}

// -------------------------------
// Continue your main backend code
// -------------------------------
?>
