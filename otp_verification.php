<?php
session_start();
include 'config.php';


if (isset($_POST['verify_otp'])) {
    $email = $_SESSION['email'];
    $entered_otp = $_POST['otp'];

    // Fetch the stored OTP and expiry time from the database
    $query = "SELECT otp, otp_expiry FROM register WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $stored_otp = $row['otp'];
    $otp_expiry = $row['otp_expiry'];
    $current_time = date("Y-m-d H:i:s");

    if ($entered_otp == $stored_otp && $current_time <= $otp_expiry) {
        // OTP is correct and within the valid time
        mysqli_query($conn, "UPDATE register SET otp_verified=1, otp=NULL, otp_expiry=NULL WHERE email='$email'");
        $_SESSION['verified'] = true; // Mark the user as verified
        header('location:home.php'); // Redirect to the home page
        exit();
    } else {
        $message = "Invalid or expired OTP!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>

<body>
    <h2>Verify OTP</h2>
    <?php if (isset($message)) { echo '<p>'.$message.'</p>'; } ?>
    
    <form action="" method="post">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <input type="submit" name="verify_otp" value="Verify OTP">
    </form>
</body>
</html>
