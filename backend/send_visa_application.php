<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../backend/visa_logger.php';

// === LOGGING ===
$logFile = __DIR__ . '/email_debug.txt';
function log_step($msg) {
    global $logFile;
    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

log_step('Script started');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    log_step('Step 1: POST detected');

    // --- Honeypot ---
    if (!empty($_POST['website'])) {
        log_step('Step 1a: Honeypot triggered');
        exit;
    }

    // --- reCAPTCHA ---
    $recaptcha_secret = '6LfsivkrAAAAAG-VXdDjFd_WnmBIqJjO5bFbc3qB';
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    $verify = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
    $captcha = json_decode($verify ?? '', true);

    if (empty($captcha['success']) || $captcha['success'] !== true) {
        log_step('Step 3a: reCAPTCHA failed');
        redirect_with_status('error');
    }

    log_step('Step 4: Passed reCAPTCHA');

    // --- Sanitize Inputs ---
    function safe($field) {
        return htmlspecialchars(trim($_POST[$field] ?? ''), ENT_QUOTES, 'UTF-8');
    }

    // === Collect All Fields Dynamically ===
    $fields = [
        'given_name', 'middle_name', 'surname', 'home_address',
        'date_of_birth', 'civil_status', 'place_of_birth', 'home_landline',
        'mobile', 'personal_email', 'company_name', 'occupation',
        'company_address', 'company_email', 'company_landline',
        'passport_number', 'date_of_issue', 'valid_until',
        'previous_visa_number', 'previous_visa_date', 'valid_from',
        'visa_valid_until', 'travel_cost', 'travel_host', 'travel_others',
        'visa_type'
    ];

    // Sanitize into an array
    $data = [];
    foreach ($fields as $f) {
        $v = safe($f);
        if ($v !== '') $data[$f] = $v; // skip blanks
    }

    // --- Default Visa Type ---
    $visa_type = $data['visa_type'] ?? 'Schengen Visa';

    // --- Build Message Dynamically ---
    $sections = [
        "PERSONAL INFORMATION" => [
            'given_name', 'middle_name', 'surname', 'home_address',
            'date_of_birth', 'civil_status', 'place_of_birth',
            'home_landline', 'mobile', 'personal_email'
        ],
        "EMPLOYMENT / SCHOOL" => [
            'company_name', 'occupation', 'company_address',
            'company_email', 'company_landline'
        ],
        "PASSPORT & VISA DETAILS" => [
            'passport_number', 'date_of_issue', 'valid_until',
            'previous_visa_number', 'previous_visa_date',
            'valid_from', 'visa_valid_until'
        ],
        "COST OF TRAVEL COVERED BY" => [
            'travel_cost', 'travel_host', 'travel_others'
        ]
    ];

    $message  = "{$visa_type} Pre-Application\n\n";
    $message .= "=============================\n";

    foreach ($sections as $title => $keys) {
        $sectionAdded = false;
        foreach ($keys as $key) {
            if (!empty($data[$key])) {
                if (!$sectionAdded) {
                    $message .= "{$title}\n";
                    $sectionAdded = true;
                }
                $label = ucwords(str_replace('_', ' ', $key));
                $message .= "{$label}: {$data[$key]}\n";
            }
        }
        if ($sectionAdded) $message .= "\n";
    }

    $message .= "=============================\n";
    $message .= "This message was automatically sent from Mitzy Travel and Tours website.\n";

    // --- Email Setup ---
    $to = 'mitzysalesdepb01@gmail.com';
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        log_step('Step 5: PHPMailer created');

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mitzywebsitemailer@gmail.com';
        $mail->Password   = 'dnqc smcb xljl paqk'; // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('mitzywebsitemailer@gmail.com', 'Mitzy Travel and Tours');
        $mail->addAddress($to);

        if (!empty($data['personal_email']) && filter_var($data['personal_email'], FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($data['personal_email'], trim(($data['given_name'] ?? '') . ' ' . ($data['surname'] ?? '')));
        }

        // Content
        $mail->isHTML(false);
        $mail->Subject = "{$visa_type} Pre-Application – " . trim(($data['given_name'] ?? '') . ' ' . ($data['surname'] ?? ''));
        $mail->Body = $message;

        log_step('Step 6: About to send');

        if ($mail->send()) {
            log_step('Step 7: SUCCESS - email sent');
            redirect_with_status('success');
        } else {
            log_step('Step 7: ERROR - ' . $mail->ErrorInfo);
            redirect_with_status('error');
        }

    } catch (Exception $e) {
        log_step('Step 8: EXCEPTION - ' . $mail->ErrorInfo);
        redirect_with_status('error');
    }

} else {
    log_step('No POST request detected');
}

// --- Helper: Redirect with status ---
function redirect_with_status($status) {
    $referer = $_SERVER['HTTP_REFERER'] ?? '../visaeu.php';
    $url = strtok($referer, '?'); // remove old query params
    header("Location: {$url}?status={$status}#form");
    exit;
}
?>
