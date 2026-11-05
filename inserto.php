<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $placed_on = date('Y-m-d');  // Use Y-m-d format for MySQL

    $product_name = isset($_POST['product_name']) ? mysqli_real_escape_string($conn, $_POST['product_name']) : '';
    $product_price = isset($_POST['product_price']) ? $_POST['product_price'] : 0;
    $product_quantity = isset($_POST['product_quantity']) ? $_POST['product_quantity'] : 0;
    $total_price = $product_price * $product_quantity;
    $total_products = $product_name . ' (' . $product_quantity . ')';

    // Default payment status
    $payment_status = ($method === 'gpay') ? 'paid' : 'pending';

    if ($total_price == 0) {
        echo 'No product selected';
    } else {
        // Inserting order details into the orders table
        $order_query = "INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) 
                        VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$total_price', '$placed_on', '$payment_status')";
        
        $result = mysqli_query($conn, $order_query);

        if ($result) {
            echo 'Order placed successfully!';
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
    }
}
?>
