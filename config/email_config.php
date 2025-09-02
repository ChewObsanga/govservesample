<?php
/**
 * Email Configuration for Caloocan City Social Services
 * Using PHPMailer for reliable email delivery
 */

// Gmail Configuration
define('GMAIL_USERNAME', 'socialgovservec3@gmail.com');
define('GMAIL_APP_PASSWORD', 'pibd tvec ilss cmxr');

// System Email Settings
define('SYSTEM_EMAIL', 'noreply@caloocan.gov.ph');
define('SYSTEM_NAME', 'Caloocan City Social Services');

// PHPMailer Autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Send Email Function - Using PHPMailer with Gmail SMTP
 * Reliable and professional email delivery
 */
function sendEmail($to, $subject, $message, $from = null, $fromName = null) {
    if (!$from) $from = GMAIL_USERNAME;
    if (!$fromName) $fromName = SYSTEM_NAME;
    
    try {
        // Create new PHPMailer instance
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = GMAIL_USERNAME;
        $mail->Password = GMAIL_APP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Recipients
        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);
        $mail->addReplyTo(SYSTEM_EMAIL, SYSTEM_NAME);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);
        
        // Send email
        $mail->send();
        
        error_log("Email sent successfully to: $to using PHPMailer");
        return true;
        
    } catch (Exception $e) {
        error_log("Email failed to send to: $to. Error: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Test Email Configuration
 */
function testEmailConfig() {
    echo "<h3>Email Configuration Test</h3>";
    echo "<p><strong>Method:</strong> PHPMailer with Gmail SMTP</p>";
    echo "<p><strong>Gmail Username:</strong> " . GMAIL_USERNAME . "</p>";
    echo "<p><strong>App Password:</strong> " . substr(GMAIL_APP_PASSWORD, 0, 4) . "****</p>";
    echo "<p><strong>Status:</strong> ✅ Configured with PHPMailer</p>";
    echo "<p><strong>Note:</strong> Professional and reliable email delivery</p>";
}
?>
