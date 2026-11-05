<?php
include 'config.php';
session_start();

// Check if user ID and status are set
if (!isset($_GET['user_id']) || !isset($_GET['status'])) {
    header('location:admin_refund.php');
    exit();
}

$user_id = $_GET['user_id'];
$status = $_GET['status'];
$message = '';

// Create the notification message
if ($status === 'approved') {
    $message = "Your refund request has been approved. Your funds will be credited to your account on the specified date.";
} else {
    $message = "Your refund request has not been approved.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Notification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="notification">
    <div class="notification_container">
        <h2>Refund Notification</h2>
        <p><?php echo $message; ?></p>
        <a href="admin_refund.php">Go Back to Refund Requests</a>
    </div>
</section>

</body>
</html>
