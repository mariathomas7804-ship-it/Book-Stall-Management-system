<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification</title>
</head>
<body>

<h1>Notification</h1>
<?php
if (isset($_SESSION['return_message'])) {
    echo "<p>{$_SESSION['return_message']}</p>";
    unset($_SESSION['return_message']); // Clear the message
}
?>

<a href="submit_return.php">Back to Dashboard</a>
</body>
</html>
