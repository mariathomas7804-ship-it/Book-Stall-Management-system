<?php
session_start();
include 'config.php'; // include database connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$email = $_SESSION['email'];
$message = ''; // Initialize message variable

// Handle password change
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verify current password
    $query = "SELECT password FROM register WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($dbPassword);
        $stmt->fetch();
        
        // Finish processing the statement before preparing a new one
        $stmt->close(); // Close the first statement before proceeding

        if (password_verify($currentPassword, $dbPassword)) {
            if ($newPassword === $confirmPassword) {
                // Update the password
                $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
                $updateQuery = "UPDATE register SET password = ? WHERE email = ?";
                $updateStmt = $conn->prepare($updateQuery);
                
                if ($updateStmt) {
                    $updateStmt->bind_param("ss", $newPasswordHash, $email);
                    
                    if ($updateStmt->execute()) {
                        $message = "<p class='success'>Password updated successfully!</p>"; // Store success message
                    } else {
                        $message = "<p class='error'>Error updating password: " . $conn->error . "</p>"; // Store error message
                    }
                    $updateStmt->close();
                } else {
                    $message = "<p class='error'>Error preparing update statement: " . $conn->error . "</p>"; // Store error message
                }
            } else {
                $message = "<p class='error'>New passwords do not match!</p>"; // Store error message
            }
        } else {
            $message = "<p class='error'>Current password is incorrect!</p>"; // Store error message
        }
    } else {
        $message = "<p class='error'>Error preparing select statement: " . $conn->error . "</p>"; // Store error message
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Background image and styling */
        body {
            font-family: Arial, sans-serif;
            background: url('backk.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Glass effect container */
        .change-password-container {
            background: rgba(255, 255, 255, 0.2); /* Transparent white background */
            backdrop-filter: blur(15px) brightness(1.2); /* Glass effect */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            padding: 30px;
            width: 400px;
            text-align: center;
            color: #333;
        }

        h1 {
            color: #4a90e2;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 16px;
            margin: 10px 0 5px;
            text-align: left;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #357ab7;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            color: #4a90e2;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="change-password-container">
        <h1>Change Password</h1>
        <form method="POST" action="">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" required>
            
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
            
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" required>

            <?php if (!empty($message)) echo $message; ?> <!-- Display message here -->
            
            <button type="submit">Change Password</button>
        </form>
        <a href="my_account.php" class="back-link">Back to My Account</a>
    </div>
</body>
</html>
