<?php
include 'config.php';
session_start();

// Check if admin is logged in
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// Fetch refund requests from the database
$select_refunds = mysqli_query($conn, "SELECT * FROM refunds") or die('Query failed: ' . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Refund Requests</title>
    <link rel="stylesheet" href="admin_refund.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="admin_refunds">
    <div class="admin_box_container">
        <h2>User Refund Requests</h2>
        
        <!-- Display any messages from previous actions (e.g., approve/deny) -->
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="message">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }

        // Check if there are refund requests
        if (mysqli_num_rows($select_refunds) > 0) {
            while ($fetch_refund = mysqli_fetch_assoc($select_refunds)) {
        ?>
            <div class="admin_box">
                <p>User ID: <span><?php echo $fetch_refund['user_id']; ?></span></p>
                <p>Order ID: <span><?php echo $fetch_refund['order_id']; ?></span></p>
                <p>Reason: <span><?php echo $fetch_refund['reason']; ?></span></p>
                <p>Request Date: <span><?php echo $fetch_refund['request_date']; ?></span></p>
                <p>Status: <span><?php echo $fetch_refund['status']; ?></span></p>
                
                <!-- Approve and Deny links for each refund request -->
                <a href="approve.php?process=<?php echo $fetch_refund['id']; ?>&action=approve" onclick="return confirm('Are you sure you want to approve this refund?');" class="approve-btn">Approve</a>
                <a href="deny.php?process=<?php echo $fetch_refund['id']; ?>&action=deny" onclick="return confirm('Are you sure you want to deny this refund?');" class="deny-btn">Deny</a>
            </div>
        <?php
            }
        } else {
            echo '<p class="empty">No refund requests at this time.</p>';
        }
        ?>
    </div>
</section>

<script src="admin_js.js"></script>
<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

</body>
</html>
