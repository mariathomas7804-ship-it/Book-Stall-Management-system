<?php
include 'config.php';
session_start();

// Check if admin is logged in
/*$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}*/

// Check if the delete request is set
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    // Delete the order from the database
    $query = "DELETE FROM orders WHERE id = '$delete_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect to orders page with success message
        header("Location: admin_order.php?message=1 order has been deleted");
        exit();
    } else {
        // Redirect to orders page with error message
        header("Location: admin_order.php?error=Failed to delete order");
        exit();
    }
} else {
    // Redirect to orders page if delete request is not set
    header("Location: admin_order.php");
    exit();
}
?>
