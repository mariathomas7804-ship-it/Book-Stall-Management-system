<?php
include 'config.php';
session_start();

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Query to fetch the book details from the database
    $select_product = mysqli_query($conn, "SELECT * FROM products WHERE id = '$book_id'") or die('query failed');

    if (mysqli_num_rows($select_product) > 0) {
        $fetch_product = mysqli_fetch_assoc($select_product);
    } else {
        echo '<p class="empty">Book not found!</p>';
    }
} else {
    echo '<p class="empty">No book selected!</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Details</title>

  <link rel="stylesheet" href="style.css"> <!-- Move styles to external file -->
  
  <style>
    .book_details {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
    }

    .book_image img {
        max-width: 300px;
        height: auto;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .book_info {
        text-align: center;
        max-width: 80%;
    }

    .book_info h2 {
        font-size: 2em;
        margin-bottom: 10px;
    }

    .book_info p {
        font-size: 1.1em;
        margin-bottom: 15px;
    }

    .product_btn {
        padding: 10px 20px;
        background-color: #C1121F;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .product_btn:hover {
        background-color: #218838;
    }
    
    .text-danger {
        color: red; /* Style for stock alert messages */
        font-weight: bold;
    }

    .btns_cart {
        width: 100%;
    }
  </style>
</head>
<body>

<?php include 'user_header.php'; ?>

<section class="book_details">
    <?php if (!empty($fetch_product)): ?>
        <div class="book_image">
            <img src="./uploaded_img/<?php echo $fetch_product['image']; ?>" alt="<?php echo $fetch_product['name']; ?>">
        </div>
        <div class="book_info">
            <h2><?php echo $fetch_product['name']; ?></h2>
            <p><strong>Price:</strong> Rs. <?php echo $fetch_product['price']; ?>/-</p>
            <p><strong>Description:</strong> <?php echo $fetch_product['description']; ?></p>

            <?php if ($fetch_product['stock'] > 0): ?>
                <?php if ($fetch_product['stock'] <= 5): ?>
                    <p class="text-danger">Only <?php echo $fetch_product['stock']; ?> left in stock!</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-danger">Out of Stock</p>
            <?php endif; ?>

            <form action="check.php" method="post">
                <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                <input type="hidden" name="product_description" value="<?php echo $fetch_product['description']; ?>">
                <input type="number" name="product_quantity" min="1" value="1">
                <input type="submit" value="Buy Now" name="buy now" class="product_btn">
                <!--<?php if(!isset($_SESSION['user_id'])): ?>
                    <button class='product_btn'><a href='login.php' style='color: white; text-decoration: none;'>Add to Cart</a></button>
                <?php else: ?>
                    <button class='product_btn'><a href='check.php' style='color: white; text-decoration: none;'>Add to Cart</a></button>
                <?php endif; ?>-->
            </form>

            <!-- Message for no return or refund policy -->
            <p style="color: black; font-weight: bold; margin-top: 10px;">
                Note: There is no return or refund policy for this product.
            </p>
        </div><br>
    <?php endif; ?>
</section>

<?php include 'footer.php'; ?>

</body>
</html>