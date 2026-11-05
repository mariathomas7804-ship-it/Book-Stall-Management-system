<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Function to calculate estimated delivery date
function calculateDeliveryDate($placedOn) {
    $orderDate = strtotime($placedOn);
    $daysToAdd = 5;

    while ($daysToAdd > 0) {
        $orderDate += 86400; // Add one day (in seconds)
        if (date('N', $orderDate) < 6) {
            $daysToAdd--;
        }
    }
    return date('Y-m-d', $orderDate);
}

// Get order details (these would typically come from a form submission)
$name = $_POST['name'];
$number = $_POST['number'];
$email = $_POST['email'];
$address = $_POST['address'];
$method = $_POST['method'];
$total_products = $_POST['total_products'];
$total_price = $_POST['total_price'];
$placed_on = date('Y-m-d');

// Calculate the estimated delivery date
$estimated_delivery_date = calculateDeliveryDate($placed_on);

// Insert the order into the database
$order_query = "INSERT INTO orders (user_id, name, number, email, address, method, total_products, total_price, placed_on, estimated_delivery_date) 
                VALUES ('$user_id', '$name', '$number', '$email', '$address', '$method', '$total_products', '$total_price', '$placed_on', '$estimated_delivery_date')";

if (mysqli_query($conn, $order_query)) {
    echo "Order placed successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
