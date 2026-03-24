<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
include '../backend/visa_logger.php';

$logFile = __DIR__ . '/email_debug_japan.txt';
function log_step($msg) {
  global $logFile;
  file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  log_step('No POST detected');
  exit;
}

if (!empty($_POST['website'])) {
  log_step('Honeypot triggered');
  exit;
}

// reCAPTCHA validation
$recaptcha_secret = '6LfsivkrAAAAAG-VXdDjFd_WnmBIqJjO5bFbc3qB';
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
$verify = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$captcha = json_decode($verify ?? '', true);

if (empty($captcha['success'])) {
  log_step('reCAPTCHA failed');
  redirect_with_status('error');
}

// Helper sanitize
function safe($f) {
  return htmlspecialchars(trim($_POST[$f] ?? ''), ENT_QUOTES, 'UTF-8');
}

// Visa type
$visa_type = safe('visa_type') ?: 'Japan Visa';

// Collect all fields
$fields = array_keys($_POST);
$data = [];
foreach ($fields as $f) {
  $v = safe($f);
  if ($v !== '') $data[$f] = $v;
}

// Build message
$sections = [
  "PERSONAL INFORMATION" => ["surname", "givenNames", "dob", "placeOfBirth", "nationality", "sex", "maritalStatus", "otherNames"],
  "PASSPORT DETAILS" => ["passportType", "passportNumber", "passportAuthority", "passportPlace", "passportIssueDate", "passportExpiryDate", "idNumber"],
  "CONTACT & EMPLOYMENT" => ["residentialAddress", "telephone", "mobile", "email", "occupation", "companyName", "companyAddress", "companyPhone", "partnerOccupation"],
  "VISIT DETAILS" => ["purposeOfVisit", "arrivalDate", "stayLength", "addressInJapan", "contactInJapan"],
  "GUARANTOR DETAILS" => ["hasGuarantor", "guarantorName", "guarantorDOB", "guarantorSex", "guarantorRelation", "guarantorAddress", "guarantorPhone", "guarantorOccupation", "guarantorStatus"],
  "INVITER DETAILS" => ["hasInviter", "inviterName", "inviterDOB", "inviterSex", "inviterRelation", "inviterAddress", "inviterPhone", "inviterOccupation", "inviterStatus"],
  "REMARKS / NOTES" => ["specialRemarks"]
];

$message = "{$visa_type} Pre-Application\n\n=============================\n";
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
$message .= "=============================\nSent from Mitzy Travel and Tours Website.\n";

// Email setup
$to = 'mitzysalesdepb01@gmail.com';
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';

try {
  log_step('Mailer init');
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'mitzywebsitemailer@gmail.com';
  $mail->Password = 'dnqc smcb xljl paqk';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  $mail->setFrom('mitzywebsitemailer@gmail.com', 'Mitzy Travel and Tours');
  $mail->addAddress($to);

  if (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $mail->addReplyTo($data['email'], trim(($data['givenNames'] ?? '') . ' ' . ($data['surname'] ?? '')));
  }

  $mail->isHTML(false);
  $mail->Subject = "{$visa_type} Pre-Application – " . trim(($data['givenNames'] ?? '') . ' ' . ($data['surname'] ?? ''));
  $mail->Body = $message;

  if ($mail->send()) {
    log_step('Email sent');
    redirect_with_status('success');
  } else {
    log_step('Send failed: ' . $mail->ErrorInfo);
    redirect_with_status('error');
  }

} catch (Exception $e) {
  log_step('Exception: ' . $e->getMessage());
  redirect_with_status('error');
}

function redirect_with_status($status) {
  $referer = $_SERVER['HTTP_REFERER'] ?? '../visajapan.php';
  $url = strtok($referer, '?');
  header("Location: {$url}?status={$status}#form");
  exit;
}
?>
