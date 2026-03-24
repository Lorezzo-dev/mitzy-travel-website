<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../backend/chat_logger.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --- reCAPTCHA Verification ---
    $recaptcha_secret = "6LfsivkrAAAAAG-VXdDjFd_WnmBIqJjO5bFbc3qB"; // 🔒 replace with your Invisible reCAPTCHA v2 secret key
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}"
    );
    $responseKeys = json_decode($response, true);

    if (empty($responseKeys["success"]) || $responseKeys["success"] !== true) {
        header("Location: ../tourinfo.php?id=" . urlencode($_POST['id']) . "&status=error#inquiry");
        exit;
    }

    // --- Honeypot Field (bot trap) ---
    if (!empty($_POST['website'])) {
        exit; // silently discard bot submissions
    }

    // --- Input Sanitization ---
    $id          = htmlspecialchars($_POST['id'] ?? '');
    $tour        = htmlspecialchars($_POST['tour'] ?? 'Unknown Tour');
    $travel_date = htmlspecialchars($_POST['travel_date'] ?? 'Not specified');
    $name        = htmlspecialchars($_POST['name']);
    $email       = htmlspecialchars($_POST['email']);
    $contact     = htmlspecialchars($_POST['contact']);
    $pax         = htmlspecialchars($_POST['pax']);
    $adults      = htmlspecialchars($_POST['adults']);
    $children    = htmlspecialchars($_POST['children']);
    $message     = nl2br(htmlspecialchars($_POST['message']));

    // --- Spam Keyword Filter ---
    if (preg_match('/seo|traffic|ranking|index|google|yahoo|bing|backlink|promotion/i', $message)) {
        exit; // discard silently
    }

    // --- Email Validation ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // --- Setup Email ---
    $to = "mitzysalesdepb01@gmail.com"; // Your main inbox

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        // --- SMTP Config ---
        // $mail->SMTPDebug = 2; // for testing
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mitzywebsitemailer@gmail.com';
        $mail->Password   = 'dnqc smcb xljl paqk'; // 16-char App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- Recipients ---
        $mail->setFrom('mitzywebsitemailer@gmail.com', 'Mitzy Travel and Tours – Tour Inquiry');
        $mail->addAddress($to);
        $mail->addReplyTo($email, $name);

        // --- Email Content ---
        $mail->isHTML(true);
        $mail->Subject = "New Tour Inquiry – $tour";
        $mail->Body = "
            <h3>New Tour Inquiry Received</h3>
            <p><strong>Tour Package:</strong> $tour</p>
            <p><strong>Selected Travel Date:</strong> $travel_date</p>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Contact:</strong> $contact</p>
            <p><strong>No. of Pax:</strong> $pax</p>
            <p><strong>Adults:</strong> $adults</p>
            <p><strong>Children:</strong> $children</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        ";

        // --- Send & Redirect ---
        if ($mail->send()) {
            header("Location: ../tourinfo.php?id=" . urlencode($id) . "&status=success#inquiry");
            exit;
        } else {
            header("Location: ../tourinfo.php?id=" . urlencode($id) . "&status=error#inquiry");
            exit;
        }
    } catch (Exception $e) {
        echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
