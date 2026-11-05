<?php
include 'config.php';
session_start();

/*$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}*/

if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];

    mysqli_query($conn, "UPDATE orders SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('Query failed: ' . mysqli_error($conn));
    $message[] = 'Order Payment status has been updated';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM orders WHERE id = '$delete_id'") or die('Query failed: ' . mysqli_error($conn));
    $message[] = '1 order has been deleted';
}

// Initialize variables for filtering
$customer_id = '';
$order_date = '';

if (isset($_POST['filter_orders'])) {
    $customer_id = trim($_POST['customer_id']);
    $order_date = trim($_POST['order_date']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin_orders table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .admin_orders th, .admin_orders td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .admin_orders th {
            background-color: #f4f4f4;
        }
        .status-pending {
            color: red;
        }
        .status-completed {
            color: green;
        }
        .filter-form {
            margin-bottom: 20px;
        }
        .filter-form input, .filter-form select {
            padding: 10px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="admin_orders">
    <h1 class="title">Placed Orders</h1>

    <!-- Filter Form -->
    <form class="filter-form" action="" method="post">
        <input type="text" name="customer_id" placeholder="Enter Customer ID" value="<?php echo htmlspecialchars($customer_id); ?>">
        <input type="date" name="order_date" value="<?php echo htmlspecialchars($order_date); ?>">
        <input type="submit" name="filter_orders" value="Filter" class="option-btn">
    </form>

    <div class="admin_box_container">
        <table>
            <thead>
                <tr>
                    <th>User Id</th>
                    <th>Placed On</th>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Build query for orders based on filter
                $query = "SELECT * FROM orders";
                $conditions = [];

                // Add conditions based on filter inputs
                if (!empty($customer_id)) {
                    $conditions[] = "user_id = '" . mysqli_real_escape_string($conn, $customer_id) . "'";
                }
                if (!empty($order_date)) {
                    $conditions[] = "DATE(placed_on) = '" . mysqli_real_escape_string($conn, $order_date) . "'";
                }

                // Append conditions to the query
                if (count($conditions) > 0) {
                    $query .= " WHERE " . implode(" AND ", $conditions);
                }

                // Debugging: Check the final query
                // echo $query; // Uncomment to see the query for debugging

                $select_orders = mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));

                if (mysqli_num_rows($select_orders) > 0) {
                    while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($fetch_orders['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_orders['placed_on']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_orders['name']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_orders['number']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_orders['email']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_orders['address']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_orders['total_products']); ?></td>
                    <td>Rs. <?php echo htmlspecialchars($fetch_orders['total_price']); ?>/-</td>
                    <td>
                        <span class="<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'status-pending' : 'status-completed'; ?>">
                            <?php echo htmlspecialchars($fetch_orders['payment_status']); ?>
                        </span>
                    </td>
                    <td>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <select name="update_payment">
                                <option disabled selected><?php echo htmlspecialchars($fetch_orders['payment_status']); ?></option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                            <input type="submit" value="Update" name="update_order" class="option-btn">
                        </form>
                       <!-- <a href="delete_order.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this order?');" class="delete-btn">Delete</a>-->

                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="10" class="empty">No orders found!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

</body>
</html>
