<?php
include 'config.php';
session_start();

// Check if admin is logged in
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// Handle approval
if (isset($_GET['process']) && isset($_GET['action']) && $_GET['action'] === 'approve') {
    $refund_id = $_GET['process'];

    // Update the refund status to "approved"
    $update_query = "UPDATE refunds SET status='approved' WHERE id='$refund_id'";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['message'] = 'Refund request approved successfully.';
    } else {
        $_SESSION['message'] = 'Update failed: ' . mysqli_error($conn);
    }

    // Redirect back to the refund requests page
    header('Location: admin_return_requests.php');
    exit();
} else {
    $_SESSION['message'] = 'Invalid request.';
    header('Location: admin_return_requests.php');
    exit();
}
?>
