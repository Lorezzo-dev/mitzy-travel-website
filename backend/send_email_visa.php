<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../backend/chat_logger.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- reCAPTCHA Verification ---
    $recaptcha_secret = "6LfsivkrAAAAAG-VXdDjFd_WnmBIqJjO5bFbc3qB"; // Your Invisible reCAPTCHA v2 secret key
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}"
    );
    $responseKeys = json_decode($response, true);

    if (empty($responseKeys["success"]) || $responseKeys["success"] !== true) {
        // CAPTCHA failed — redirect back safely
        $redirect_url = $_POST['current_page'] ?? ($_SERVER['HTTP_REFERER'] ?? '../index.php');
        $redirect_url = strtok($redirect_url, '?');
        header("Location: " . $redirect_url . "?status=error#visa-contact-form");
        exit;
    }

    // --- Honeypot (anti-bot hidden field) ---
    if (!empty($_POST['website'])) {
        exit; // silently discard spam bots
    }

    // --- Sanitize Inputs ---
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    $visa_type = htmlspecialchars($_POST['visa_type'] ?? 'General Visa Inquiry');

    // --- Spam Keyword Filter ---
    if (preg_match('/seo|traffic|ranking|index|google|yahoo|bing|promotion|backlink/i', $message)) {
        exit; // silently drop spam
    }

    // --- Validate Email ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // --- Email Destination ---
    $to = "mitzysalesdepb01@gmail.com"; // Inbox for all visa inquiries

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        // --- SMTP CONFIGURATION ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mitzywebsitemailer@gmail.com';
        $mail->Password   = 'dnqc smcb xljl paqk'; // 16-char App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- RECIPIENTS ---
        $mail->setFrom('mitzywebsitemailer@gmail.com', 'Mitzy Travel and Tours');
        $mail->addAddress($to);
        $mail->addReplyTo($email, $name);

        // --- EMAIL CONTENT ---
        $mail->isHTML(false); // Use plain text for reliability
        $mail->Subject = "Visa Inquiry: $visa_type – from $name";
        $mail->Body =
            "You have received a new visa inquiry via the Mitzy Travel and Tours website.\n\n" .
            "============================\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Visa Type: $visa_type\n" .
            "============================\n\n" .
            "Message:\n$message\n\n" .
            "------------------------------------\n" .
            "This message was automatically sent from the Mitzy Travel and Tours Visa Inquiry Form.";

        // --- SAFER DYNAMIC REDIRECT DETECTION ---
        $redirect_url = $_POST['current_page'] ?? '';
        if (empty($redirect_url)) {
            $redirect_url = $_SERVER['HTTP_REFERER'] ?? '';
        }
        if (empty($redirect_url)) {
            $redirect_url = '../index.php'; // final fallback
        }

        $redirect_url = strtok($redirect_url, '?'); // remove old query params
        $status_anchor = '#visa-contact-form';

        // --- SEND & REDIRECT ---
        if ($mail->send()) {
            header("Location: " . $redirect_url . "?status=success{$status_anchor}");
            exit;
        } else {
            header("Location: " . $redirect_url . "?status=error{$status_anchor}");
            exit;
        }

    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
