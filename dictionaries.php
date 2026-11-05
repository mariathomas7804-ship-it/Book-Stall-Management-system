<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictionaries & Language Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .book-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .book-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            text-align: left;
            width: 22%;
            transition: transform 0.3s ease;
            text-decoration: none; /* Remove underline */
            color: inherit; /* Inherit text color */
        }
        .book-card:hover {
            transform: translateY(-10px);
        }
        .book-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .book-title {
            font-size: 20px;
            color: #2c3e50;
        }
        .book-details {
            font-size: 16px;
            color: #555;
        }
        .book-details p {
            margin: 5px 0;
        }
        .buy-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .buy-button:hover {
            background-color: #218838;
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
    <h1>Dictionaries & Language Books</h1>
    
    <!-- Search form -->
    <div class="search-bar">
        <form method="POST">
            <input type="text" name="search" placeholder="Search for books..." required>
            <input type="submit" value="Search">
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

    // SQL query to select dictionaries books
    $sql = "SELECT * FROM products WHERE prod_type = 'Dictionaries & Language'";
    
    // Check if search is performed
    if (isset($_POST['search'])) {
        $search_term = $conn->real_escape_string($_POST['search']);
        $sql .= " AND name LIKE '%$search_term%'";
    }

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Output data for each row
        while ($row = $result->fetch_assoc()) {
            echo "<a href='book_details.php?product_id=" . htmlspecialchars($row['id']) . "' class='book-card'>"; // Make the card clickable
            echo "<img class='book-image' src='uploaded_img/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
            echo "<h2 class='book-title'>" . htmlspecialchars($row['name']) . "</h2>";
            echo "<div class='book-details'>";
            echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
            echo "</div>";
            echo "<span class='buy-button'>Buy Now</span>"; // Optional: Change this to just a span
            echo "</a>";
        }
    } else {
        echo "<p>No Dictionaries & Language books found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
    </div>
</div>

</body>
</html>