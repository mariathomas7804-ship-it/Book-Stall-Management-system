<?php
session_start();
include 'config.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details (if necessary)
$user_result = mysqli_query($conn, "SELECT * FROM register WHERE id = '$user_id'") or die('Query failed');
$user = mysqli_fetch_assoc($user_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>

<nav>
    <ul>
        <li><a href="view_books.php">View Books</a></li>
        <li><a href="submit_return.php">Submit Return Request</a></li>
        <li><a href="view_notifications.php">View Notifications</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<h2>Your Return Requests</h2>
<table>
    <thead>
        <tr>
            <th>Request ID</th>
            <th>Book ID</th>
            <th>Reason</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch user's return requests
        $requests = mysqli_query($conn, "SELECT * FROM return_requests WHERE user_id = '$user_id'") or die('Query failed');
        while ($request = mysqli_fetch_assoc($requests)): ?>
            <tr>
                <td><?php echo $request['id']; ?></td>
                <td><?php echo $request['prod_id']; ?></td>
                <td><?php echo htmlspecialchars($request['reason']); ?></td>
                <td><?php echo htmlspecialchars($request['status']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
