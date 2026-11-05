<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Law Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 85%;
            max-width: 1500px;
            margin: 20px auto;
            text-align: left;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }
        .book-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        .book-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            text-align: center;
            width: 24%;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .book-title {
            font-size: 20px;
            color: #2c3e50;
            margin: 10px 0;
        }
        .book-details {
            font-size: 16px;
            color: #555;
        }
        .book-details p {
            margin: 5px 0;
        }
        .book-image {
            width: 50%;
            height: 230px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
        }
        .search-bar input[type="submit"] {
            padding: 10px 15px;
            background-color: #C1121F;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: #A10C1B;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Law Books</h1>
    
    <!-- Search form -->
    <div class="search-bar">
        <form method="POST">
            <input type="text" name="search_query" placeholder="Search for law books...">
            <input type="submit" name="search" value="Search">
        </form>
    </div>

    <div class="book-grid">
    <?php
    // Database connection
    $host = "localhost";  // Your database host
    $dbUsername = "root"; // Your database username
    $dbPassword = "";     // Your database password
    $dbName = "books";    // Your database name

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Search functionality
    $search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';
    if ($search_query) {
        $sql = "SELECT * FROM products WHERE prod_type = 'Law' AND name LIKE '%" . $conn->real_escape_string($search_query) . "%'";
    } else {
        $sql = "SELECT * FROM products WHERE prod_type = 'Law'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data for each row
        while ($row = $result->fetch_assoc()) {
            echo "<div class='book-card' onclick=\"window.location='book_details.php?book_id=" . $row['id'] . "'\">";
            echo "<img class='book-image' src='uploaded_img/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
            echo "<h2 class='book-title'>" . htmlspecialchars($row['name']) . "</h2>";
            echo "<div class='book-details'>";
            echo "<p>Price: ₹" . htmlspecialchars($row['price']) . "</p>";
            echo "</div>";
            echo "</div>"; 
        }
    } else {
        echo "<p>No law books found.</p>";
    }

    // Close the database connection
    $conn->close(); 
    ?>
    </div>
</div>

</body>
</html>