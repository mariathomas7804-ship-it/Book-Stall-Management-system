<?php
include 'config.php';
session_start();

// Check if 'add_to_cart' is triggered
if (isset($_POST['add_to_cart'])) {
    $pro_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $pro_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $pro_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $pro_quantity = mysqli_real_escape_string($conn, $_POST['product_quantity']); // Quantity input

    // Set user_id, use 0 for guests if session isn't set
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    // Check if the product is already in the cart
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id' AND name = '$pro_name'") or die(mysqli_error($conn));

    if (mysqli_num_rows($check) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO cart (user_id, name, price, quantity, image) 
                             VALUES ('$user_id', '$pro_name', '$pro_price', '$pro_quantity', '$pro_image')") 
                             or die(mysqli_error($conn));
        $message[] = 'Product added to cart!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="home.css">

    <style>
        .book_link {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        .book_link:hover {
            color: black;
        }
    </style>
</head>
<body>

<?php include 'user_header.php'; ?>

<section class="search_cont">
    <form action="" method="post">
        <input type="text" name="search" placeholder="Search Products......">
        <input type="submit" value="Search" name="submit" class="product_btn">
    </form>
</section>

<section class="products_cont">
    <div class="pro_box_cont">
        <?php
        if (isset($_POST['submit'])) {
            $search_item = mysqli_real_escape_string($conn, $_POST['search']);
            $select_products = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '%{$search_item}%'") or die(mysqli_error($conn));
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <form action="" method="post" class="pro_box">
                        <a href="book_details.php?book_id=<?php echo $fetch_products['id']; ?>" class="book_link">
                            <img src="./uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
                            <h3><?php echo $fetch_products['name']; ?></h3>
                        </a>
                        <p>Stock: <?php echo $fetch_products['stock']; ?></p>
                        <p>Rs. <?php echo $fetch_products['price']; ?>/-</p>

                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name'] ?>">
                        <input type="number" name="product_quantity" min="1" value="1">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                        <input type="submit" value="Add to Cart" name="add_to_cart" class="product_btn">
                    </form>
                    <?php
                }
            } else {
                echo '<p class="empty">No result found!</p>';
            }
        } else {
            echo '<p class="empty">Search Something!</p>';
        }
        ?>
    </div>
</section>

<section class="products_cont">
    <div class="pro_box_cont">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM products") or die(mysqli_error($conn));

        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                ?>
                <form action="" method="post" class="pro_box">
                    <a href="book_details.php?book_id=<?php echo $fetch_products['id']; ?>" class="book_link">
                        <img src="./uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
                        <h3><?php echo $fetch_products['name']; ?></h3>
                    </a>
                    <p>Stock: <?php echo $fetch_products['stock']; ?></p>
                    <p>Rs. <?php echo $fetch_products['price']; ?>/-</p>
                    
                    <input type="hidden" name="product_name" value="<?php echo $fetch_products['name'] ?>">
                   <!-- <input type="number" name="product_quantity" min="1" value="1">-->
                    <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                    <input type="submit" value="Add to Cart" name="add_to_cart" class="product_btn">
                </form>
                <?php
            }
        } else {
            echo '<p class="empty">No Products Added Yet!</p>';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>
</body>
</html>
