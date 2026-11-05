<?php
session_start();
include 'config.php'; // Include your database connection settings

// Check if user is logged in
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
}

// Handle refund request submission
if (isset($_POST['submit_refund'])) {
   // $order_id = $_POST['order_id'];
    $reason = $_POST['reason'];

    // Insert refund request into database (assuming you have a refunds table)
    $insert_refund = mysqli_query($conn, "INSERT INTO refunds (user_id, reason) VALUES ('$user_id', '$reason')") or die('Query failed');

    if ($insert_refund) {
        $message[] = 'Refund request submitted successfully!';
    } else {
        $message[] = 'Failed to submit refund request!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Policy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'user_header.php'; ?>

<section class="refund_policy">
    <h1>Refund Policy</h1>
    <p>We aim to ensure your satisfaction with your purchase. If you are not satisfied, you can request a refund within 30 days of purchase.</p>
    
    <h2>Conditions for Refund</h2>
    <ul>
        <li>Items must be in original condition.</li>
        <li>Refund requests must be submitted within 30 days of purchase.</li>
        <li>Proof of purchase is required.</li>
    </ul>

    <h2>Submit a Refund Request</h2>
    <form action="" method="post">
        
        <label for="reason">Reason for Refund:</label>
        <textarea name="reason" required></textarea>

        <input type="submit" value="Submit Refund Request" name="submit_refund">
    </form>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo "<p>$msg</p>";
        }
    }
    ?>
</section>

<?php include 'footer.php'; ?>
</body>
</html>