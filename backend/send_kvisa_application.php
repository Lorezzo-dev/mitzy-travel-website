<?php
// send_kvisa_application.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../backend/visa_logger.php';

// === LOGGING ===
$logFile = __DIR__ . '/kvisa_email_debug.txt';
function log_step($msg) {
    global $logFile;
    file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

log_step('K-Visa script started');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    log_step('No POST, exiting');
    redirect_with_status('error');
}

// Honeypot
if (!empty($_POST['website'])) {
    log_step('Honeypot triggered - exit');
    exit;
}

// reCAPTCHA
$recaptcha_secret = '6LfsivkrAAAAAG-VXdDjFd_WnmBIqJjO5bFbc3qB';
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

$verify = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$captcha = json_decode($verify ?? '', true);

if (empty($captcha['success']) || $captcha['success'] !== true) {
    log_step('reCAPTCHA failed');
    redirect_with_status('error');
}

log_step('reCAPTCHA passed');

// safe function
function safe($field) {
    return htmlspecialchars(trim($_POST[$field] ?? ''), ENT_QUOTES, 'UTF-8');
}

// list of possible fields used in the Korea form
$fields = [
    // Personal
    'familyname','givennames','sex','dob','nationality','countryOfBirth','nationalId',
    'otherNamesUsed','otherNamesDetails',

    // Passport
    'passportType','otherPassportTypeDetails','passportNumber','passportCountry',
    'placeOfIssue','dateOfIssue','dateOfExpiry',
    'otherPassportSelect','otherPassportType','otherPassportNumber','otherPassportCountry','otherPassportExpiry',

    // Contact & Family
    'homeAddress','currentAddress','cellPhone','telephoneNo','email',
    'emergencyName','emergencyCountry','emergencyPhone','emergencyRelationship',
    'maritalStatus','spouseFamilyName','spouseGivenNames','spouseDOB','spouseNationality','spouseAddress','spouseContact',
    'hasChildren','numberOfChildren',

    // Education & Employment
    'highestDegree','otherEducation','schoolName','schoolLocation',
    'personalCircumstances','otherEmployment','companyName','positionCourse','companyAddress','companyPhone',

    // Visit / Invitation / Funding
    'purposeOfVisit','otherPurpose','periodOfStay','intendedDate','koreaAddress','koreaContact',
    'travelKorea5yrs','travelKoreaNotes',
    'travelOther','travelOtherNotes',
    'familyInKorea','familyInKoreaNotes',
    'otherFamilyInKorea','otherFamilyInKoreaNotes',
    'invitedBy','inviterName','inviterDOB','inviterRelationship','inviterAddress','inviterPhone',
    'travelCostUSD','sponsorName','sponsorRelationship','supportType','sponsorContact',

    // System
    'visa_type'
];

// Sanitize into an array
$data = [];
foreach ($fields as $f) {
    $v = safe($f);
    if ($v !== '') $data[$f] = $v;
}

// default visa type
$visa_type = $data['visa_type'] ?? 'Korean Visa';

// Build message grouped into sections
$sections = [
    "PERSONAL INFORMATION" => [
        'familyname','givennames','sex','dob','nationality','countryOfBirth','nationalId','otherNamesUsed','otherNamesDetails'
    ],
    "PASSPORT INFORMATION" => [
        'passportType','otherPassportTypeDetails','passportNumber','passportCountry','placeOfIssue','dateOfIssue','dateOfExpiry',
        'otherPassportSelect','otherPassportType','otherPassportNumber','otherPassportCountry','otherPassportExpiry'
    ],
    "CONTACT & EMERGENCY" => [
        'homeAddress','currentAddress','cellPhone','telephoneNo','email',
        'emergencyName','emergencyCountry','emergencyPhone','emergencyRelationship'
    ],
    "MARITAL & FAMILY" => [
        'maritalStatus','spouseFamilyName','spouseGivenNames','spouseDOB','spouseNationality','spouseAddress','spouseContact',
        'hasChildren','numberOfChildren'
    ],
    "EDUCATION" => [
        'highestDegree','otherEducation','schoolName','schoolLocation'
    ],
    "EMPLOYMENT" => [
        'personalCircumstances','otherEmployment','companyName','positionCourse','companyAddress','companyPhone'
    ],
    "DETAILS OF VISIT" => [
        'purposeOfVisit','otherPurpose','periodOfStay','intendedDate','koreaAddress','koreaContact',
        'travelKorea5yrs','travelKoreaNotes','travelOther','travelOtherNotes'
    ],
    "FAMILY & TRAVEL COMPANIONS" => [
        'familyInKorea','familyInKoreaNotes','otherFamilyInKorea','otherFamilyInKoreaNotes'
    ],
    "INVITATION" => [
        'invitedBy','inviterName','inviterDOB','inviterRelationship','inviterAddress','inviterPhone'
    ],
    "FUNDING DETAILS" => [
        'travelCostUSD','sponsorName','sponsorRelationship','supportType','sponsorContact'
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
    log_step('PHPMailer create');

    // SMTP settings (same as China file)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mitzywebsitemailer@gmail.com';
    $mail->Password   = 'dnqc smcb xljl paqk'; // Gmail App Password (as used in your example)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('mitzywebsitemailer@gmail.com', 'Mitzy Travel and Tours');
    $mail->addAddress($to);

    if (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $mail->addReplyTo($data['email'], trim(($data['familyname'] ?? '') . ' ' . ($data['givennames'] ?? '')));
    }

    // Content
    $mail->isHTML(false);
    $mail->Subject = "{$visa_type} Pre-Application – " . trim(($data['familyname'] ?? '') . ' ' . ($data['givennames'] ?? ''));
    $mail->Body = $message;

    log_step('About to send email');

    if ($mail->send()) {
        log_step('Email sent successfully');
        redirect_with_status('success');
    } else {
        log_step('Email send failed: ' . $mail->ErrorInfo);
        redirect_with_status('error');
    }

} catch (Exception $e) {
    log_step('PHPMailer Exception: ' . $mail->ErrorInfo . ' / ' . $e->getMessage());
    redirect_with_status('error');
}

// --- Helper: Redirect with status ---
function redirect_with_status($status) {
    $referer = $_SERVER['HTTP_REFERER'] ?? '../visakorea.php';
    $url = strtok($referer, '?'); // remove old query params
    header("Location: {$url}?status={$status}#form");
    exit;
}
?>
