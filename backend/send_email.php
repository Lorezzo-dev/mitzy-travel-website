

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../backend/chat_logger.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- Basic input sanitization ---
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // --- Honeypot check (stop bots that fill hidden field) ---
    if (!empty($_POST['website'])) {
        exit; // silently ignore spam
    }

    // --- Basic spam keyword filter ---
    if (preg_match('/seo|traffic|ranking|index|google|yahoo|bing/i', $message)) {
        exit; // ignore spam messages
    }

    // --- Validate email ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // --- reCAPTCHA verification ---
    $recaptcha_secret = "6LfsivkrAAAAAG-VXdDjFd_WnmBIqJjO5bFbc3qB"; // <-- Replace with your actual secret key
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    // Verify the CAPTCHA with Google's API
    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}"
    );
    $responseKeys = json_decode($response, true);

    if (empty($responseKeys["success"]) || $responseKeys["success"] !== true) {
        header("Location: ../contact.php?status=error#contact-form");
        exit;
    }

    // --- Email configuration ---
    $to = "mitzysalesdepb01@gmail.com"; // where you receive the inquiries

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        // --- SMTP Configuration ---
        // $mail->SMTPDebug = 2; // Enable only for testing
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mitzywebsitemailer@gmail.com';
        $mail->Password   = 'dnqc smcb xljl paqk'; // 16-char App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- Recipients ---
        $mail->setFrom('mitzywebsitemailer@gmail.com', 'Mitzy Travel and Tours');
        $mail->addAddress($to);
        $mail->addReplyTo($email, $name);

        // --- Message Content ---
        $mail->isHTML(false);
        $mail->Subject = "New Inquiry from $name – $subject";
        $mail->Body =
            "You have received a new message from the Mitzy Travel and Tours website.\n\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Subject: $subject\n" .
            "Message:\n$message\n";

        // --- Send and Redirect ---
        if ($mail->send()) {
            header("Location: ../contact.php?status=success#contact-form");
            exit;
        } else {
            header("Location: ../contact.php?status=error#contact-form");
            exit;
        }
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
