<?php
session_start();
include 'config.php';

if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['email'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = $conn->prepare("UPDATE register SET password = ? WHERE email = ?");
        if ($query) {
            $query->bind_param("ss", $hashed_password, $email);
            if ($query->execute()) {
                echo "Password reset successful!";
                session_destroy();
                header("Location: login.php");
                exit();
            } else {
                echo "Password reset failed. Please try again.";
            }
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    } else {
        echo "Passwords do not match. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            background-image: url('backk.png');
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-container {
            
            background: rgba(255, 255, 255, 0.2); /* Transparent white background */
            backdrop-filter: blur(15px) brightness(1.2); /* Glass effect */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: #333;
        }
        

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
            text-align: left;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <form method="post" action="">
            <h2>Reset Password</h2>
            <label>New Password:</label>
            <input type="password" name="new_password" required>
            
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    </div>
</body>
</html>
