<?php
session_start();
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    $query = $conn->prepare("SELECT * FROM register WHERE email = ?");
    if ($query) {
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'nalandaadimali03@gmail.com';
                $mail->Password = 'jwzmearaazwhbtjl';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('your-email@gmail.com', 'Nalanda Bookstall');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP';
                $mail->Body    = "Your OTP for password reset is: <b>$otp</b>";
                $mail->AltBody = "Your OTP for password reset is: $otp";

                $mail->send();
                echo "OTP sent to your email. Please check your inbox.";
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Email not found.";
        }
    } else {
        echo "Failed to prepare the SQL statement.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            background-image: url('backk.png');
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Glass effect container */
        .forgot-container {
            background: rgba(255, 255, 255, 0.2); /* Transparent white background */
            backdrop-filter: blur(15px) brightness(1.2); /* Glass effect */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: #fff;
        }

        h2 {
            color: #28a745;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 16px;
            margin: 10px 0 5px;
            text-align: left;
            color: #333;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <form method="post" action="">
            <h2>Forgot Password</h2>
            <label>Email:</label>
            <input type="email" name="email" required>
            <button type="submit" name="submit">Send OTP</button>
        </form>
    </div>
</body>
</html>
