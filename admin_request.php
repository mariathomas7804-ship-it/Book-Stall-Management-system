<?php
session_start();
include 'config.php'; // Database connection

// Check if user is admin
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}

if (isset($_GET['approve']) || isset($_GET['deny'])) {
    $request_id = $_GET['id'];
    $status = isset($_GET['approve']) ? 'approved' : 'denied';

    // Update the return request status
    $update_request = mysqli_query($conn, "UPDATE return_requests SET status = '$status' WHERE id = '$request_id'") or die('Query failed');

    // Get user_id to notify the user
    $result = mysqli_query($conn, "SELECT user_id FROM return_requests WHERE id = '$request_id'");
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['user_id'];

    // Notify the user (you can implement this via email or session)
    if ($update_request) {
        $message = ($status === 'approved') ? 'Your return request has been approved.' : 'Your return request has been denied.';
        mysqli_query($conn, "INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$message')");
    }

    header('location:admin_return_requests.php');
    exit();
}

// Fetch all return requests
$return_requests = mysqli_query($conn, "SELECT * FROM return_requests WHERE status = 'pending'") or die('Query failed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Return Requests</title>
</head>
<body>
<h1>Pending Return Requests</h1>
<table>
    <tr>
        <th>Request ID</th>
        <th>User ID</th>
        <th>Book ID</th>
        <th>Reason</th>
        <th>Actions</th>
    </tr>
    <?php while ($request = mysqli_fetch_assoc($return_requests)): ?>
        <tr>
            <td><?php echo $request['id']; ?></td>
            <td><?php echo $request['user_id']; ?></td>
            <td><?php echo $request['book_id']; ?></td>
            <td><?php echo $request['reason']; ?></td>
            <td>
                <a href="?approve=true&id=<?php echo $request['id']; ?>">Approve</a>
                <a href="?deny=true&id=<?php echo $request['id']; ?>">Deny</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
