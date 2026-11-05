<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);
try{
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';         // Your Gmail address
$mail->Password = 'your-app-password';           // Your app-specific password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use 'tls' for port 587
$mail->Port = 465;                               // Port 587 for STARTTLS or 465 for SMTPS
                           

    //Recipients
    $mail->setFrom('your-email@gmail.com', 'Your Name');
    $mail->addAddress('recipient@example.com');              

    //Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email message.';

    $mail->send();
    echo 'Message has been sent';
}catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
