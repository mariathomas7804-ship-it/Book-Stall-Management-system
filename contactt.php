<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nalanda - Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
         
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #ecf0f1;
        }
        .contact-container {
            background-color: #34495e;
            border-radius: 15px;
            padding: 30px;
            width: 350px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .contact-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .contact-info {
            text-align: left;
            margin-bottom: 20px;
        }
        .contact-info p {
            margin: 10px 0;
        }
        .contact-info i {
            margin-right: 10px;
        }
        .contact-image {
            margin: 20px auto;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid#ecf0f1;
        }
        .contact-image img {
            width: 100%;
            height: auto;
        }
        
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="contact-container">
    <h1>Contact Us</h1>
    <div class="contact-image">
        <img src="c.png" alt="Contact Image">
    </div>
    <div class="contact-info">
        <p><i class="fa fa-phone"></i> Phone: <?php echo "xxxxxxxxxx"; ?></p>
        <p><i class="fa fa-envelope"></i> Email: <?php echo "nalanda@gmail.com"; ?></p>
        <p><i class="fa fa-map-marker"></i> Address: <?php echo "adimali"; ?></p>
    </div>
</div>

</body>
</html>