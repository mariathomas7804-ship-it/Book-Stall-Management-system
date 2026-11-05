<?php
session_start();
include 'config.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit_return'])) {
    $book_id = $_POST['id']; // Assuming book_id is passed from a book page
    $reason = mysqli_real_escape_string($conn, $_POST['reason']); // Escape input for security

    // Insert the return request into the database
    $insert_request = mysqli_query($conn, "INSERT INTO return_requests (user_id, id, reason) VALUES ('$user_id', '$id', '$reason')");

    if (!$insert_request) {
        die('Query failed: ' . mysqli_error($conn)); // Display the specific error
    } else {
        $_SESSION['return_message'] = 'Return request submitted successfully!';
        header('location:notification.php'); // Redirect to a notification page
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Return Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"> <!-- Get book_id from the URL -->
    <label for="reason">Reason for Return:</label>
    <textarea name="reason" required></textarea>
    <input type="submit" name="submit_return" value="Submit Return Request">
</form>
<?php include 'footer.php'; ?>
</body>
</html>
