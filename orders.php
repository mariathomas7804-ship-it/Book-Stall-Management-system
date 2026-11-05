<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit(); // Stop further script execution
}

$user_id = $_SESSION['user_id']; // Retrieve user ID from session

// Function to calculate estimated delivery date
function calculateDeliveryDate($placedOn) {
    // Convert placedOn to a timestamp
    $orderDate = strtotime($placedOn);
    
    // Add 5 business days to the order date
    $daysToAdd = 5;
    $weekendDays = 0;

    while ($daysToAdd > 0) {
        $orderDate += 86400; // Add one day (in seconds)
        
        // Check if the new date is a weekend (Saturday or Sunday)
        if (date('N', $orderDate) < 6) {
            // Not a weekend, count this day
            $daysToAdd--;
        } else {
            // It's a weekend, count as a weekend day
            $weekendDays++;
        }
    }

    return date('Y-m-d', $orderDate); // Format the delivery date
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="home.css">

</head>
<body>
  
<?php
include 'user_header.php'; // Include the user header
?>

<section class="orders">
    <h2>Placed Orders</h2>
    <div class="orders_cont">
        <?php
        // Fetch orders from the database
        $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id'") or die('query failed');

        if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
                $estimatedDeliveryDate = calculateDeliveryDate($fetch_orders['placed_on']); // Calculate delivery date
        ?>
        <div class="orders_box">
            <p>Placed on: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
            <p>Name: <span><?php echo $fetch_orders['name']; ?></span></p>
            <p>Number: <span><?php echo $fetch_orders['number']; ?></span></p>
            <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
            <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
            <p>Payment method: <span><?php echo $fetch_orders['method']; ?></span></p>
            <p>Your orders: <span><?php echo $fetch_orders['total_products']; ?></span></p>
            <p>Total price: <span>$<?php echo $fetch_orders['total_price']; ?>/-</span></p>
            <p>Payment status: <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>;"><?php echo $fetch_orders['payment_status']; ?></span></p>
            <p>Estimated Delivery Date: <span><?php echo $estimatedDeliveryDate; ?></span></p> <!-- Display estimated delivery date -->
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>
    </div>
</section>

<?php
include 'footer.php'; // Include the footer
?>
<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>
<script src="script.js"></script>

</body>
</html>