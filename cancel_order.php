<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit(); // Stop further script execution
}

$user_id = $_SESSION['user_id']; // Retrieve user ID from session

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id']; // Get the order ID from the form

    // Update the order status to 'cancelled' in the database
    $cancel_query = mysqli_query($conn, "UPDATE orders SET status='cancelled' WHERE id='$order_id' AND user_id='$user_id'") or die('query failed');

    if ($cancel_query) {
        // Send cancellation notification to admin
        $admin_email = "admin@example.com"; // Replace with your admin email
        $subject = "Order Cancellation Notification";
        $message = "Order ID $order_id has been cancelled by user ID $user_id.";
        mail($admin_email, $subject, $message); // Send email notification

        echo '<p class="success">Order has been cancelled successfully.</p>';
    } else {
        echo '<p class="error">Failed to cancel the order. Please try again.</p>';
    }
} else {
    echo '<p class="error">Invalid request.</p>';
}

// Redirect back to orders page after processing
header('Location: orders.php');
exit();
?>
