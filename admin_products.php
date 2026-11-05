<?php
include 'config.php';
session_start();

/*$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}*/

if (isset($_POST['add_products_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $stock = $_POST['stock']; // New field for stock
    $prod_type = mysqli_real_escape_string($conn, $_POST['prod_type']); // New field for product type

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = "uploaded_img/" . $image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM products WHERE name='$name'") or die('query failed');

    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'The given product is already added';
    } else {
        if ($image_size > 2000000) {
            $message[] = 'Image size is too large';
        } else {
            $add_product_query = mysqli_query($conn, "INSERT INTO products(name, price, description, stock, prod_type, image) VALUES ('$name', '$price', '$description', '$stock', '$prod_type', '$image')") or die('query2 failed'); // Insert product type
            if ($add_product_query) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = "Product added successfully!";
            } else {
                $message[] = "Product failed to be added!";
            }
        }
    }
}




if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

 
    $delete_img_query = mysqli_query($conn, "SELECT image FROM products WHERE id='$delete_id'") or die('query failed');
    $fetch_del_img = mysqli_fetch_assoc($delete_img_query);
    
   
    mysqli_query($conn, "DELETE FROM products WHERE id='$delete_id'") or die('query failed');
    
  
    header('location:admin_products.php');
}



if (isset($_POST['update_product'])) {
    
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];
    $update_description = mysqli_real_escape_string($conn, $_POST['update_description']);
    $update_stock = $_POST['update_stock']; // Get updated stock value
    $update_prod_type = mysqli_real_escape_string($conn, $_POST['update_prod_type']); // Get updated product type

    mysqli_query($conn, "UPDATE products SET name='$update_name', price='$update_price', description='$update_description', stock='$update_stock', prod_type='$update_prod_type' WHERE id='$update_p_id'") or die('query failed'); // Update product type

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = './uploaded_img/' . $update_image;
    $old_image = $_POST['update_old_img'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image size is too large';
        } else {
            mysqli_query($conn, "UPDATE products SET image='$update_image' WHERE id='$update_p_id'") or die('query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('./uploaded_img/' . $old_image);
            $message[] = "Product updated successfully!";
        }
    }
    header('location:admin_products.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="admin_add_products">
    <form action="" method="post" enctype="multipart/form-data" class="mb-4">
        <h3>Add Product</h3>
        <input type="text" name="name" class="form-control mb-2" placeholder="Enter Product Name" required>
        <input type="number" min="0" name="price" class="form-control mb-2" placeholder="Enter Product Price" required>
       
        <textarea name="description" class="form-control mb-2" placeholder="Enter Product Description" ></textarea>
        <input type="number" min="0" name="stock" class="form-control mb-2" placeholder="Enter Stock Count" required>
        <input type="text" name="prod_type" class="form-control mb-2" placeholder="Enter Product Type" required> <!-- New field for product type -->
        <input type="file" name="image" class="form-control mb-2" accept="image/jpg, image/jpeg, image/png" required>
        <input type="submit" name="add_products_btn" class="btn btn-success w-100" value="Add Product">
    </form>
</section>

<section class="show_products">
    <div class="container">
        <div class="row justify-content-center">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');

            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <!-- Bootstrap column and inline styling for product boxes -->
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="product_box" style="border-radius: 20px; box-shadow: 2px 2px 15px gray; padding: 1rem; height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                        <img src="./uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" style="width: 140px; max-height: 180px; border-radius: 10px; display: block; margin: 0 auto;">

                            <div class="product_name mt-2" style="font-weight: bold;"><?php echo $fetch_products['name']; ?></div>
                            <div class="product_price" style="color: gray;">Rs. <?php echo $fetch_products['price']; ?> /-</div>
                            <div class="product_description" style="overflow: hidden; text-overflow: ellipsis;"><?php echo $fetch_products['description']; ?></div>
                            <div class="product_stock">Stock: <?php echo $fetch_products['stock']; ?></div>
                            <div class="product_type">Type: <?php echo $fetch_products['prod_type']; ?></div>

                            <!-- Warning for low stock -->
                            <?php if ($fetch_products['stock'] <= 5): ?>
                                <div class="stock_warning text-danger font-weight-bold">
                                    Only <?php echo $fetch_products['stock']; ?> left in stock!
                                </div>
                            <?php endif; ?>

                            <!-- Action buttons -->
                            <div class="mt-2">
                                <a href="admin_products.php?update=<?php echo $fetch_products['id'] ?>" class="btn btn-primary w-100 mb-2">Update</a>
                                <a href="admin_products.php?delete=<?php echo $fetch_products['id'] ?>" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No product added yet!</p>';
            }
            ?>
        </div>
    </div>
</section>
<section class="edit_product_form">
    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_query = mysqli_query($conn, "SELECT * FROM products WHERE id='$update_id'") or die('query failed');
        if (mysqli_num_rows($update_query) > 0) {
            while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                    <input type="hidden" name="update_old_img" value="<?php echo $fetch_update['image']; ?>">
                    <img src="./uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                    <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="admin_input update_box" required placeholder="Enter Product Name">
                    <input type="number" name="update_price" min="0" value="<?php echo $fetch_update['price']; ?>" class="admin_input update_box" required placeholder="Enter Product Price">
                    <textarea name="update_description" class="admin_input update_box" required placeholder="Enter Product Description"><?php echo $fetch_update['description']; ?></textarea>
                    <input type="number" name="update_stock" min="0" value="<?php echo $fetch_update['stock']; ?>" class="admin_input update_box" required placeholder="Enter Stock Count">
                    <input type="text" name="update_prod_type" value="<?php echo $fetch_update['prod_type']; ?>" class="admin_input update_box" required placeholder="Enter Product Type"> <!-- New field for updating product type -->
                    <input type="file" name="update_image" class="admin_input update_box" accept="image/jpg, image/jpeg, image/png">
                    <input type="submit" value="Update" name="update_product" class="product_btn">
                    <input type="reset" value="Cancel" id="close_update" class="product_btn product_del_btn">
                </form>

                <?php
            }
        }
    } else {
        echo "<script>
        document.querySelector('.edit_product_form').style.display='none';
        </script>";
    }
    ?>
</section>


<!-- Bootstrap JS, jQuery, and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="admin_js.js"></script>
<script src="https://kit.fontawesome.com/eedbcd0c96.js" crossorigin="anonymous"></script>

</body>
</html>