<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if (isset($_POST['send'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = $_POST['number'];
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
  
    $select_message = mysqli_query($conn, "SELECT * FROM message WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');
  
    if (mysqli_num_rows($select_message) > 0) {
        $message[] = 'Message sent already!';
    } else {
        mysqli_query($conn, "INSERT INTO message(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
        $message[] = 'Message sent successfully!';
    }
}

// Fetch replies for the user
$user_messages = mysqli_query($conn, "SELECT * FROM message WHERE user_id = '$user_id'") or die('query failed');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Page</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="home.css">
  <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #ecf0f1;
         
            min-height: 100vh;
        }

        header, footer {
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #34495e;
            color: #ecf0f1;
        }

        .contact_us {
            flex: 1;
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }

        .contact_us form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact_us h2, .contact-container h1 {
            font-size: 24px;
        }

        .message_box, .contact-container {
            background-color: #34495e;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            margin-top: 20px;
        }

        .contact-container {
            width: 100%;
            max-width: 400px;
        }

        .contact-image {
            margin: 0 auto;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #ecf0f1;
        }

        .contact-image img {
            width: 100%;
            height: auto;
        }

        input[type="text"], input[type="email"], input[type="phone"], textarea {
            padding: 10px;
            border-radius: 5px;
            border: none;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #e74c3c;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
  </style>
</head>
<body>
  
<?php
include 'user_header.php';
?>

<section class="contact_us">
    <form action="" method="post">
       <h2>Contact Us!</h2>
       <input type="text" name="name" required placeholder="Enter your name">
       <input type="email" name="email" required placeholder="Enter your email">
       <input type="phone" name="number" required placeholder="Enter your number">
       <textarea name="message" placeholder="Enter your message" id="" cols="30" rows="10"></textarea>
       <input type="submit" value="Send Message" name="send" class="product_btn">
       
       <h2>Your Messages</h2>
       <div class="message_box">
           <?php if (mysqli_num_rows($user_messages) > 0): ?>
               <?php while ($msg = mysqli_fetch_assoc($user_messages)): ?>
                   <div>
                       <strong><?php echo $msg['name']; ?>:</strong>
                       <p><?php echo $msg['message']; ?></p>
                   </div>
                   <?php if ($msg['reply']): ?>
                       <div>
                           <strong>Admin:</strong>
                           <p><?php echo $msg['reply']; ?></p>
                       </div>
                   <?php else: ?>
                       <p><em>No reply yet.</em></p>
                   <?php endif; ?>
               <?php endwhile; ?>
           <?php else: ?>
               <p class="empty">You have no messages!</p>
           <?php endif; ?>
       </div>
    </form>
</section>

<div class="contact-container">
    <h1>Contact Us</h1>
    <div class="contact-image">
        <img src="c.png" alt="Contact Image">
    </div>
    <div class="contact-info">
        <p><i class="fa fa-phone"></i> Phone: <?php echo "xxxxxxxxxx"; ?></p>
        <p><i class="fa fa-envelope"></i> Email: <?php echo "nalanda@gmail.com"; ?></p>
        <p><i class="fa fa-map-marker"></i> Address: <?php echo "adimali"; ?></p>
    </div>
</div>

<?php
include 'footer.php';
?>
<script src="script.js"></script>

</body>
</html>