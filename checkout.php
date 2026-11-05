<?php
include 'config.php';
session_start();

$user_id=$_SESSION['user_id'];

if(!isset($user_id)){
  header('location:login.php');
}

if(isset($_POST['order_btn'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $number = $_POST['number'];
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $method = mysqli_real_escape_string($conn, $_POST['method']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $placed_on = date('d-M-Y');

  $cart_total = 0;
  $cart_products[] = '';

  $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(' ',$cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }else{
      if(mysqli_num_rows($order_query) > 0){
         $message[] = 'order already placed!'; 
      }else{
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'order placed successfully!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      }
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
  
<?php
include 'user_header.php';
?>

<section class="display_order">
  <h2>Ordered Products</h2>
  <?php
    $grand_total=0;
    $select_cart=mysqli_query($conn,"SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');

    if(mysqli_num_rows($select_cart)>0){
      while($fetch_cart=mysqli_fetch_assoc($select_cart)){
        $total_price=($fetch_cart['price'] * $fetch_cart['quantity']);
        $grand_total+=$total_price;
      
  ?>
  <div class="single_order_product">
    <img src="./uploaded_img/<?php echo$fetch_cart['image'];?>" alt="">
    <div class="single_des">
    <h3><?php echo $fetch_cart['name'];?></h3>
    <p>Rs. <?php echo $fetch_cart['price'];?></p>
    <p>Quantity : <?php echo $fetch_cart['quantity'];?></p>
    </div>

  </div>
  

  <?php
  }
}else{
  echo '<p class="empty">your cart is empty</p>';
}
  ?>
  <div class="checkout_grand_total"> GRAND TOTAL : <span>$<?php echo $grand_total; ?>/-</span> </div>
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
