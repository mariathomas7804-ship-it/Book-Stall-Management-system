<?php
include 'config.php';
session_start();

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `message` WHERE id='$delete_id'");
    $message[] = '1 message has been deleted';
    header("location:admin_messages.php");
}

if (isset($_POST['reply'])) {
    $message_id = $_POST['message_id'];
    $reply = mysqli_real_escape_string($conn, $_POST['reply_message']);
    
    mysqli_query($conn, "UPDATE `message` SET reply = '$reply' WHERE id = '$message_id'") or die('query failed');
    $reply_message[] = 'Reply sent successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages</title>
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
include 'admin_header.php';
?>

<section class="admin_messages">
  <div class="admin_box_container">
    <?php
      $select_msgs = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
      if (mysqli_num_rows($select_msgs) > 0) {
        while ($fetch_msgs = mysqli_fetch_assoc($select_msgs)) {  
    ?>
    <div class="admin_box">
      <p>Name: <span><?php echo $fetch_msgs['name']; ?></span></p>
      <p>Number: <span><?php echo $fetch_msgs['number']; ?></span></p>
      <p>Email: <span><?php echo $fetch_msgs['email']; ?></span></p>
      <p>Message: <span><?php echo $fetch_msgs['message']; ?></span></p>
      <p>Reply: <span><?php echo $fetch_msgs['reply'] ? $fetch_msgs['reply'] : 'No reply yet.'; ?></span></p>
      
      <form action="" method="post">
          <input type="hidden" name="message_id" value="<?php echo $fetch_msgs['id']; ?>">
          <textarea name="reply_message" placeholder="Enter your reply here..." cols="30" rows="3"></textarea>
          <input type="submit" name="reply" value="Send Reply" class="reply-btn">
      </form>
      <a href="admin_messages.php?delete=<?php echo $fetch_msgs['id']; ?>" onclick="return confirm('Are you sure you want to delete this message?');" class="delete-btn">delete</a>
    </div>
    <?php
        }
      } else {
        echo '<p class="empty">You Have No Messages!</p>';
      }
    ?>
  </div>
</section>

<script src="admin_js.js"></script>
<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

</body>
</html>
