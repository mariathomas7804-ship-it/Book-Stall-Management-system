<?php
include 'config.php';
// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['add_to_cart'])) {

    if(!isset($_SESSION['user_id'])){
        header('Location: login.php');
    }
    else{
        $pro_name = $_POST['product_name'];
        $pro_price = $_POST['product_price'];
        $pro_image = $_POST['product_image'];
        $user_id = $_SESSION['user_id'];

        $check = mysqli_query($conn, "SELECT * FROM cart WHERE name='$pro_name' AND user_id='$user_id'") or die('query failed');

        if (mysqli_num_rows($check) > 0) {
            $message[] = 'Already added to cart!';
        } else {
            mysqli_query($conn, "INSERT INTO cart(user_id,name,price,image) VALUES ('$user_id','$pro_name','$pro_price','$pro_image')") or die('query2 failed');
            $message[] = 'Product added to cart!';
        }
        header("Location : cart.php");
    }

    // $pro_id = array();
    // $fetch_id = mysqli_query($conn, "SELECT id FROM products WHERE name='$'")
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="home.css">

    <style>
        .pro_box {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px;
        }

        .pro_box img {
            max-width: 250px; /* Adjust the width for book images */
            height: auto;
        }

        .product_btn {
            padding: 12px 25px; /* Increase padding for better size */
            background-color: #C1121F;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Smooth transition on hover */
            text-align: center; /* Center text */
            font-size: 18px; /* Increase font size for better visibility */
            margin: 10px 0; /* Space between button and other elements */
            min-width: 120px; /* Minimum width for the button */
        }

        .product_btn:hover {
            background-color: #218838; /* Change background color on hover */
        }
    </style>
</head>

<body>

    <?php
    include 'user_header.php';
    ?>

    <section class="home_cont">
        <div class="main_descrip">
            <h1>The Bookshelf</h1>
            <p>Explore, Discover, and Buy Your Favorite Books</p>
        </div>
    </section>

    <section class="products_cont">
        <div class="pro_box_cont">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM products LIMIT 12") or die('query failed');

            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
                    <form action="" method="post" class="pro_box">
                        <a href="book_details.php?book_id=<?php echo $fetch_products['id']; ?>">
                            <img src="./uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                        </a>
                        <h3>
                            <a href="book_details.php?book_id=<?php echo $fetch_products['id']; ?>" style="text-decoration: none; color: black;">
                                <?php echo $fetch_products['name']; ?>
                            </a>
                        </h3>
                        <p>Rs. <?php echo $fetch_products['price']; ?>/-</p>
                        <!--<h6>/*<?php echo $fetch_products['description']; ?>*/</h6>-->

                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                       <!--<input type="hidden" name="product_description" value="<?php echo $fetch_products['description']; ?>">-->

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

    <section class="about_cont">
        <img src="about.jpg" alt="">
        <div class="about_descript">
            <h2>Discover Our Story</h2>
            <p>At Nalanda, we are passionate about connecting readers with captivating stories, inspiring ideas, and a world of knowledge. Our bookstore is more than just a place to buy books; it's a haven for book enthusiasts, where the love for literature thrives.</p>
            <button class="product_btn" onclick="window.location.href='about.php';">Read More</button>
        </div>
    </section>
    
    <?php 
    include 'category.php';
    ?>
    
    <?php
    include 'footer.php';
    ?>

</body>

</html>
