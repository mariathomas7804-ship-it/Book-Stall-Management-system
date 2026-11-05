<?php
session_start();
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$message = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Invalid email format!';
    } else {
        if (strlen($password) < 8 || !preg_match('/[\W]/', $password)) {
            $message[] = 'Password must be at least 8 characters long and include at least one special character.';
        } elseif ($password !== $confirm_password) {
            $message[] = 'Passwords do not match!';
        } else {
            $select_users = mysqli_query($conn, "SELECT * FROM register WHERE email='$email'") or die('query failed');

            if (mysqli_num_rows($select_users) > 0) {
                $message[] = 'User already exists! Please log in.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $verification_code = rand(100000, 999999);

                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['hashed_password'] = $hashed_password;
                $_SESSION['user_type'] = 'user'; // Default user type as 'user'
                $_SESSION['verification_code'] = $verification_code;

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'nalandaadimali03@gmail.com'; 
                    $mail->Password = 'jwzmearaazwhbtjl';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('your-email@gmail.com', 'Nalanda Bookstall');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your Email Verification Code';
                    $mail->Body = "<p>Your verification code is: <strong>$verification_code</strong></p>";

                    $mail->send();

                    header("Location: verify.php");
                    exit();
                } catch (Exception $e) {
                    $message[] = "Error: Could not send verification email. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register and OTP Verification</title>

    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
         /* Page background with image */
         body {
            background-image: url('backk.png'); /* Replace with the path to your image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        /* Glass effect container box */
        .box {
            /* background: rgba(255, 255, 255, 0.2); Semi-transparent background */
            backdrop-filter: blur(10px); /* Blur effect */
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 400px;
            color: #fff; /* Text color */
        }

        .box h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .inputbox {
            position: relative;
            margin-bottom: 30px;
        }

        .inputbox input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 5px;
            background: transparent;
            color: #fff;
            outline: none;
        }

        .inputbox span {
            position: absolute;
            left: 10px;
            top: 10px;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
            transition: 0.3s;
        }

        .inputbox input:focus ~ span,
        .inputbox input:valid ~ span {
            top: -15px;
            font-size: 12px;
            color: #fff;
        }

        .links a {
            color: #00bfff;
            text-decoration: none;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

    </style>

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordMessage = document.getElementById('passwordMessage');

            const passwordRegex = /^(?=.*[\W])(?=.*[a-zA-Z]).{8,}$/;
            if (!passwordRegex.test(password)) {
                passwordMessage.textContent = "Password must be at least 8 characters long and include at least one special character.";
                return false;
            }

            if (password !== confirmPassword) {
                passwordMessage.textContent = "Passwords do not match.";
                return false;
            }

            passwordMessage.textContent = "";
            return true;
        }
    </script>
</head>
<body>
    <div class="box">
        <h2>Register</h2>
        
        <?php
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo '<div class="message"><span>'.$msg.'</span><i class="fa-solid fa-xmark" onclick="this.parentElement.remove();"></i></div>';
            }
        }
        ?>

        <form action="emailotp.php" method="POST" onsubmit="return validatePassword()">
            <div class="inputbox">
                <label for="username">Name :</label>
                <input type="text" name="name" required="required">
            </div>

            <div class="inputbox">
                <label for="username">Email:</label>
                <input type="email" name="email" required="required">
            </div>

            <div class="inputbox">
                <label for="username">Password:</label>
                <input type="password" name="password" id="password" required="required">
            </div>

            <div class="inputbox">
                <label for="username">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required="required">
            </div>

            <div id="passwordMessage" style="color: red; font-size: 14px;"></div>

            <input type="submit" value="Register Now" class="submit-btn">
        </form>
    </div>
</body>
</html>
