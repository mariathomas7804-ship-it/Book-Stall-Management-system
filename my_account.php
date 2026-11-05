<?php
session_start();
include 'config.php'; // include database connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user details from the database
$email = $_SESSION['email'];
$query = "SELECT * FROM register WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->

    <style>
        /* General body styling with background image */
        body {
            font-family: Arial, sans-serif;
            background: url('backk.png') no-repeat center center fixed; /* Replace 'background.jpg' with your image file path */
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        /* Back button styling */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .back-button:hover {
            background-color: #2980b9;
        }

        /* Container for the account section with glass effect */
        .account-container {
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent white */
            backdrop-filter: blur(10px); /* Adds blur effect */
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        /* Account title styling */
        .account-container h1 {
            color:rgba(6, 19, 9, 0.88);
            margin-bottom: 20px;
        }

        /* Account information styling */
        .account-info p {
            font-size: 16px;
            margin: 8px 0;
        }

        /* Bold text for labels */
        .account-info p strong {
            color: #555;
        }

        /* Account actions styling */
        .account-actions {
            margin-top: 20px;
        }

        .account-actions a {
            display: inline-block;
            margin: 5px 10px;
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            background-color: #28a745;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .account-actions a:hover {
            background-color: #28a745;
        }

        /* Responsive design */
        @media (max-width: 500px) {
            .account-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <a href="home.php" class="back-button">Go Back</a>
    
    <div class="account-container">
        <h1>My Account</h1>
        
        <div class="account-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <!-- Add more fields if needed -->
        </div>

        <div class="account-actions">
            <a href="edit_account.php">Edit Account</a>
            <a href="change_password.php">Change Password</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
