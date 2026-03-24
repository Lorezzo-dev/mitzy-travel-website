<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../backend/chat_logger.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- reCAPTCHA Verification ---
    $recaptcha_secret = "6LfsivkrAAAAAG-VXdDjFd_WnmBIqJjO5bFbc3qB"; // Replace with your invisible reCAPTCHA v2 secret key
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}"
    );
    $responseKeys = json_decode($response, true);

    if (empty($responseKeys["success"]) || $responseKeys["success"] !== true) {
        header("Location: ../home.php?status=error#home-contact");
        exit;
    }

    // --- Honeypot Field (anti-bot trap) ---
    if (!empty($_POST['website'])) {
        exit; // silently discard
    }

    // --- Input Sanitization ---
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // --- Spam keyword filter ---
    if (preg_match('/seo|traffic|ranking|index|google|yahoo|bing/i', $message)) {
        exit;
    }

    // --- Email Validation ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // --- Email Configuration ---
    $subject = "Quick Inquiry from Homepage";
    $to = "mitzysalesdepb01@gmail.com"; // destination inbox

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mitzywebsitemailer@gmail.com';
        $mail->Password   = 'dnqc smcb xljl paqk'; // 16-char App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('mitzywebsitemailer@gmail.com', 'Mitzy Travel and Tours - Quick Inquiry');
        $mail->addAddress($to);
        $mail->addReplyTo($email, $name);

        $mail->isHTML(false);
        $mail->Subject = "New Quick Inquiry from $name";
        $mail->Body =
            "You have received a quick inquiry from the homepage form.\n\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Message:\n$message\n";

        if ($mail->send()) {
            header("Location: ../home.php?status=success#home-contact");
            exit;
        } else {
            header("Location: ../home.php?status=error#home-contact");
            exit;
        }
    } catch (Exception $e) {
        echo "❌ Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
