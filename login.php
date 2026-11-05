<?php
session_start();
include('config.php'); // Your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['email'] = $row['email']; // Store email if needed

            // Redirect based on user type
            if ($row['user_type'] === 'admin') {
                header("Location: admin_page.php");
                exit();
            } else if($row['user_type']=='user'){
                // header("Location: home.php");
                $_SESSION['user_id'] = $row['id'];
                header("Location: http://localhost/model/home.php");
                exit();
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Font Awesome Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Page background with image */
        body {
            background-image: url('backk.png'); /* Replace with the path to your image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        /* Glass effect container */
        .box {
            /*background: rgba(255, 255, 255, 0.2); /* Semi-transparent background */
            backdrop-filter: blur(10px); /* Blur effect for glass effect */
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 400px;
            color: #fff; /* Text color */
        }

        .box h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .inputbox {
            position: relative;
            margin-bottom: 30px;
        }

        .inputbox input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 5px;
            background: transparent;
            color: #fff;
            outline: none;
        }

        .inputbox span {
            position: absolute;
            left: 10px;
            top: 10px;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
            transition: 0.3s;
        }

        .inputbox input:focus ~ span,
        .inputbox input:valid ~ span {
            top: -15px;
            font-size: 12px;
            color: #fff;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .error {
            color: #d9534f;
            margin-top: 20px;
        }

        .links a {
            color: #00bfff;
            text-decoration: none;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Login</h2>

    <form action="" method="POST">
        <div class="inputbox">
            <label for="email">Email:</label>
            <input type="email" name="email" required placeholder="Email">
        </div>

        <div class="inputbox">
            <label for="password">Password:</label>
            <input type="password" name="password" required placeholder="Password">
        </div>

        <input type="submit" name="login" value="Login" class="submit-btn">
    </form>

    <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>

    <div class="links">
        <a href="forgot_password.php">Forgot Password</a> | <a href="emailotp.php">Register</a>
    </div>
</div>

</body>
</html>
