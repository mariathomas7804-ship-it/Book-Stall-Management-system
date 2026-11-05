<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit(); // Always use exit after header redirection
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate product details from POST request
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];
    
    // Check stock availability
    $product_check = mysqli_query($conn, "SELECT stock FROM products WHERE name='$product_name'") or die('query failed');
    $product_data = mysqli_fetch_assoc($product_check);
    
    if ($product_data && $product_data['stock'] >= $product_quantity) {
        // Deduct stock
        $new_stock = $product_data['stock'] - $product_quantity;
        mysqli_query($conn, "UPDATE products SET stock='$new_stock' WHERE name='$product_name'") or die('stock update failed');
    } else {
        die('Not enough stock available!');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="home.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
</head>
<body>

<?php include 'user_header.php'; ?>

<section class="display_order">
    <h2>Ordered Product</h2>
    <div class="single_order_product">
        <img src="./uploaded_img/<?php echo isset($_POST['product_image']) ? $_POST['product_image'] : 'default.jpg'; ?>" alt="">
        <div class="single_des">
            <h3><?php echo isset($_POST['product_name']) ? $_POST['product_name'] : 'Product Name'; ?></h3>
            <p>Price: Rs. <?php echo isset($_POST['product_price']) ? $_POST['product_price'] : '0'; ?></p>
            <p>Quantity: <?php echo isset($_POST['product_quantity']) ? $_POST['product_quantity'] : '0'; ?></p>
        </div>
    </div>
    <div class="checkout_grand_total"> GRAND TOTAL: <span>Rs. <?php echo (isset($_POST['product_price']) ? $_POST['product_price'] : 0) * (isset($_POST['product_quantity']) ? $_POST['product_quantity'] : 0); ?>/-</span> </div>
</section>

<section class="contact_us">
    <form id="order_form">
        <h2>Add Your Details</h2>
        <input type="text" name="name" required placeholder="Enter your name">
        <input type="phone" name="number" required placeholder="Enter your number">
        <input type="email" name="email" required placeholder="Enter your email">
        <select name="method" id="payment_method" required>
            <option value="cash on delivery">Cash on Delivery</option>
            <option value="gpay">GPay</option>
        </select>
        <textarea name="address" required placeholder="Enter your address" cols="30" rows="10"></textarea>

        <!-- Hidden fields to pass product details -->
        <input type="hidden" name="product_name" value="<?php echo isset($_POST['product_name']) ? $_POST['product_name'] : ''; ?>">
        <input type="hidden" name="product_price" value="<?php echo isset($_POST['product_price']) ? $_POST['product_price'] : 0; ?>">
        <input type="hidden" name="product_quantity" value="<?php echo isset($_POST['product_quantity']) ? $_POST['product_quantity'] : 0; ?>">
        <input type="hidden" name="product_image" value="<?php echo isset($_POST['product_image']) ? $_POST['product_image'] : ''; ?>">

        <button type="submit" class="product_btn" id="order_btn">Place Your Order</button>
    </form>
</section>

<?php include 'footer.php'; ?>

<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

<script>
    $('#order_form').submit(function(e) {
        e.preventDefault();

        const paymentMethod = $('#payment_method').val();
        if (paymentMethod === 'gpay') {
            var options = {
                "key": "rzp_test_PFA30deltBek3L", // Razorpay Key ID
                "amount": <?php echo (isset($_POST['product_price']) ? $_POST['product_price'] : 0) * 100; ?>,
                "currency": "INR",
                "name": "Nalanda Bookstall",
                "description": "Book Purchase",
                "image": "https://example.com/your_logo",
                "handler": function (response) {
                    Swal.fire('Success!', 'Payment successful! Order ID: ' + response.razorpay_payment_id, 'success');
                    insertOrder(); // Insert order to the database after successful payment
                },
                "prefill": {
                    "name": $('input[name="name"]').val(),
                    "email": $('input[name="email"]').val()
                },
                "theme": {
                    "color": "#3399cc"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        } else {
            insertOrder(); // For Cash on Delivery, directly insert the order
        }
    });

    function insertOrder() {
        $.ajax({
            url: 'inserto.php',
            type: 'POST',
            data: $('#order_form').serialize(),
            success: function(response) {
                Swal.fire('Success!', response, 'success'); // Show success message
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', 'Failed to place order. Try again later.', 'error');
            }
        });
    }
</script>

</body>
</html>