<header class="user_header">
  <div class="header_1">
    <div class="user_flex">
      <div class="logo_cont">
        <img src="bookk.png" alt="">
        <a href="home.php" class="book_logo">nalanda</a>
      </div>

      <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="about.php">About</a>

        <!-- Books Dropdown Start -->
        <div class="dropdown">
          <a href="shop.php" class="dropbtn">Books</a>
          <div class="dropdown-content">
            <a href="artphotography.php">Art & Photography</a>
            <a href="biographies.php">Biographies & Memoirs</a>
            <a href="literature.php">Literature & Literary Studies</a>
            <a href="fiction.php">Fiction</a>
            <a href="reference.php">References & Encyclopedias</a>
            <a href="history.php">History & Humanities</a>
            <a href="social.php">Society & Social Sciences</a>
           
            <a href="nonfiction.php">Non Fiction</a>
            <a href="romance.php">Romance</a>
            <a href="comics.php">Comics</a>
            <a href="travel.php">Travel</a>
            <a href="medical.php">Medical</a>
          </div>
        </div>
        <!-- Books Dropdown End -->
        <a href="orders.php">Orders</a>
        <a href="contact.php">Contact</a>
       
      </nav>
        <?php 
          if(!isset($_SESSION['user_id'])){
             echo "<div class='last_part'>
        <div class='loginorreg'>
          <p>New <a href='login.php'>Login</a> | <a href='emailotp.php'>Register</a></p>
        </div>";

          } else{
            echo "<div class='dropdown'>
            <a href='home.php' class='dropbtn'><i class='fas fa-user'></i> </a>
            <div class='dropdown-content account-dropdown'>
            
                <a href='my_account.php'><i class='far fa-user'></i> Your Account</a>                
                <a href='orders.php'><i class='fas fa-shopping-bag'></i> Your Orders</a>
                <a href='wishlist.php'><i class='fas fa-shopping-cart'></i> Your cart</a>
                <a href='gift_certificates.php'><i class='fas fa-undo'></i> return</a>
                <a href='logout.php' class='delete-btn'><i class='fas fa-sign-out-alt'></i> Logout</a>
              
                <p>Please <a href='login.php'>Login</a> to access account details</p>
            </div>
          </div>";
          }
        ?>
        <div class="icons">
          <a class="fa-solid fa-magnifying-glass" href="search_page.php"></a>

          <!-- Account Dropdown Start -->
          
          <!-- Account Dropdown End -->

          <?php
          // if($user_id){
          //     $select_cart_number = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id'") or die('query failed');
          //     $cart_row_number = mysqli_num_rows($select_cart_number);
          // } else {
          //     $cart_row_number = 0;
          // }
          ?>
          <a href="cart.php"><i class="fas fa-shopping-cart"></i><span class="quantity"></span></a>

          <div class="fas fa-bars" id="user_menu_btn"></div>
        </div>
      </div>
    </div>
  </div>
</header>

<style>
  /* General navbar styling */
  .fas.fa-user {
    color: white;
}

  .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
  }

  .navbar a {
      padding: 14px 16px;
      text-decoration: none;
      color: #333;
      font-family: Arial, sans-serif;
  }

  .navbar a:hover {
      background-color: #ddd;
      color: black;
  }

  /* Dropdown menu styling */
  .dropdown {
      position: relative;
      display: inline-block;
  }

  .dropbtn {
      padding: 14px 16px;
      cursor: pointer;
      color: #333;
      font-family: Arial, sans-serif;
      text-decoration: none;
      display: flex;
      align-items: center;
  }

  .dropbtn i {
      margin-right: 8px;
  }

  .dropdown:hover .dropbtn {
      background-color: #ddd;
  }

  .dropdown-content {
      display: none;
      position: absolute;
      background-color: black;
      min-width: 200px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
      border: 1px solid #ccc;
  }

  .dropdown-content a {
      color: white;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      display: flex;
      align-items: center;
  }

  .dropdown-content a i {
      margin-right: 10px;
  }

  .dropdown:hover .dropdown-content {
      display: block;
  }

  .dropdown-content a:hover {
      background-color: #f2f2f2;
  }

  /* Account dropdown specific styling */
  .account-dropdown {
      top: 100%;
      right: 0;
  }

  .account-dropdown a {
      text-align: left;
  }

  /* Optional: Style for messages */
  .message {
      background-color: #f9c2c2;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      display: flex;
      justify-content: space-between;
      align-items: center;
  }

  .message span {
      margin-right: 10px;
  }

  .delete-btn {
      background-color: #d9534f;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
  }

  /* Fixing the dropdown alignment with other menu items */
  .dropdown, .navbar a {
      display: inline-block;
      vertical-align: middle;
  }

  /* Ensure dropdown aligns properly */
  .navbar {
      display: flex;
      align-items: center;
  }

  .last_part {
      display: flex;
      align-items: center;
  }

  /* Style for cart quantity */
  .quantity {
      /background-color: red;/
      color: white;
      padding: 2px 6px;
      border-radius: 50%;
      margin-left: 5px;
  }

  /* Style for icons in the header */
  .icons {
      display: flex;
      align-items: center;
  }

  /* Styling for the bars (menu) icon */
  .fa-bars {
      margin-left: 10px;
      cursor: pointer;
  }
</style>