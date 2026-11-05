<?php
session_start();
include 'config.php'; // include database connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$email = $_SESSION['email'];

// Fetch user details from the database
$query = "SELECT * FROM register WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Initialize message variable
$message = "";
$updateSuccess = false; // To track if update was successful

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $newEmail = $_POST['email'];

    // Update user details
    $updateQuery = "UPDATE register SET name = ?, email = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sss", $name, $newEmail, $email);
    
    // Execute the update statement
    if ($updateStmt->execute()) {
        $_SESSION['email'] = $newEmail; // Update session email
        $message = "<p class='success'>Account updated successfully!</p>";
        $updateSuccess = true; // Set to true if update is successful
        
        // Fetch the updated user details
        $query = "SELECT * FROM register WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $newEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); // Update the user variable with new data
    } else {
        $message = "<p class='error'>Error updating account: " . $conn->error . "</p>";
    }

    $updateStmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Background styling */
        body {
            font-family: Arial, sans-serif;
            background-image: url('backk.png'); /* Add your image path here */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Glass effect container styling */
        .account-container {
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent white */
            backdrop-filter: blur(10px); /* Frosted glass effect */
            padding: 30px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            color: #28a745;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 16px;
            margin: 10px 0 5px;
            text-align: left;
            color: #333;
        }

        input[type="text"],
        input[type="email"] {
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
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #28a745;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            color:rgb(9, 10, 9);
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .success {
            color: black;
            font-size: 14px;
            margin-top: 10px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .account-details {
            margin-top: 20px;
            text-align: left;
        }

        .account-details p {
            margin: 5px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="account-container">
        <h1>Edit Account</h1>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <button type="submit">Update Account</button>
        </form>

        <!-- Display message -->
        <?php if ($message) { echo $message; } ?>

        <!-- Display updated account details -->
        <?php if ($updateSuccess) { ?>
            <div class="account-details">
                <h2>Your Updated Account Details</h2>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        <?php } ?>

        <a href="my_account.php" class="back-link">Back to My Account</a>
    </div>
</body>
</html>
