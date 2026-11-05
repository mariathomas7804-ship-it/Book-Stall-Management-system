<?php
session_start();
require 'database_connection.php'; // replace with your actual database connection file

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    
    // Check if email exists
    $query = $conn->prepare("SELECT * FROM register WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch();
    
    if ($user) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP to user's email (You can use PHPMailer or similar)
        mail($email, "Password Reset OTP", "Your OTP is: $otp");

        echo "OTP sent to your email. Please check your inbox.";
        header("Location: verify_otp.php");
    } else {
        echo "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <form method="post" action="">
        <h2>Forgot Password</h2>
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit" name="submit">Send OTP</button>
    </form>
</body>
</html>
