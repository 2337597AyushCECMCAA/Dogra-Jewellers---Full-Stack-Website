<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){

      $row = mysqli_fetch_assoc($select_users);

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');

      }

   }else{
      $message[] = 'incorrect email or password!';
   }

}

if(isset($_POST['send'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .form-container {
         min-height: 40vh;
      }
      
      :root{   --purple:#dfb51a;
      }
      /* .heading{
         background: url(../images/heading-bg2.png) no-repeat;
      } */
   </style>

</head>

<body>

   <?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

   <!-- Navbar -->
   <header class="header">

      <div class="header-1">
         <div class="flex">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <p> New <a href="#login">Login</a> | <a href="register.php">Register</a> </p>
         </div>
      </div>

      <div class="header-2">
         <div class="flex">
            <a href="login.php" Class="logo"><img src="images/logo.png" style="height:6rem;"></a>

            <nav class="navbar">
               <a href="#home">Home</a>
               <a href="#about">About</a>
               <a href="#login">Shop</a>
               <a href="#contact">Contact</a>
               <a href="#login">Orders</a>
            </nav>

            <div class="icons">
               <div id="menu-btn" class="fas fa-bars"></div>
               <a href="#login" class="fas fa-search"></a>
               <div id="user-btn" class="fas fa-user"></div>
               <a href="#login"> <i class="fas fa-shopping-cart"></i> <span></span> </a>
            </div>

            <div class="user-box">
               <p>Username : <span><?php echo $_SESSION['user_name']; ?></span></p>
               <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p>
               <a href="logout.php" class="delete-btn">Logout</a>
            </div>
         </div>
      </div>

   </header>
   <!-- Navebar-End -->

   <!-- Header -->
   <section id="home" class="home">
      <div class="content">
         <h3>Hand Picked Jewelry To Your Door.</h3>
         <p>Best Jewelry Of Best Times.
         </p>
         <a href="#about" class="white-btn">Discover More</a>
      </div>

   </section>
   <!-- Header-End -->

   <!-- Products -->
   <section id="products" class="products">

      <h1 class="title">Latest Products</h1>

      <div class="box-container">

         <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
         ?>
         <form action="" method="post" class="box">
            <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
            <input type="number" min="1" name="product_quantity" value="1" class="qty">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
            <input type="submit" value="add to cart" name="add_to_cart" class="btn">
         </form>
         <?php
         }
         }else{
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>
      </div>
   </section>
      <!-- Products-End -->

      <!-- About -->
      <section id="about" class="about">

         <div class="flex">

            <div class="image">
               <img src="images/aboutimg1.jpg" alt="">
            </div>

            <div class="content">
               <h3>About Us</h3>
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt ad, quo labore fugiat nam accusamus quia. Ducimus repudiandae dolore placeat.</p>
               <a href="#login" class="btn">Read More</a>
            </div>

         </div>

      </section>
      <!-- About-End -->

      <!-- Contact -->
      <section id="contact" class="home-contact">

         <div class="content">
            <h3>Have Any Questions?</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Atque cumque exercitationem repellendus, amet
               ullam voluptatibus?</p>
         </div>

         <section class="contact">

   <form action="" method="post">
      <h3>Say Something!</h3>
      <input type="text" name="name" required placeholder="Enter Your Name" class="box">
      <input type="email" name="email" required placeholder="Enter Your Email" class="box">
      <input type="number" name="number" required placeholder="Enter Your Number" class="box">
      <textarea name="message" class="box" placeholder="Enter Your Message" id="" cols="30" rows="10"></textarea>
      <input type="submit" value="Send Message" name="send" class="btn">
   </form>

</section>


      </section>
      <!-- Contact-End -->

      <!-- Login -->
      <div id="login" class="form-container">

         <form action="" method="post">
            <h3>Login Now</h3>
            <input type="email" name="email" placeholder="Enter Your Email" required class="box">
            <input type="password" name="password" placeholder="Enter Your Password" required class="box">
            <input type="submit" name="submit" value="Login Now" class="btn">
            <p>Don't Have An Account? <a href="register.php">Register Now</a></p>
         </form>

      </div>
      <!-- Login-End -->

      <!-- Footer -->
      <section class="footer">

         <div class="box-container">

            <div class="box" style="text-align:left;border:none; background-color:inherit;box-shadow:none;">
               <h3>Quick links</h3>
               <a href="home.php">Home</a>
               <a href="about.php">About</a>
               <a href="shop.php">Shop</a>
               <a href="contact.php">Contact</a>
            </div>

            <div class="box" style="text-align:left;border:none; background-color:inherit;box-shadow:none;">
               <h3>Extra Links</h3>
               <a href="login.php">Login</a>
               <a href="register.php">Register</a>
               <a href="cart.php">Cart</a>
               <a href="orders.php">Orders</a>
            </div>

            <div class="box" style="text-align:left;border:none; background-color:inherit;box-shadow:none;">
               <h3>Contact Info</h3>
               <p> <i class="fas fa-phone"></i> +919876543210 </p>
               <p> <i class="fas fa-phone"></i> +918907654321 </p>
               <p> <i class="fas fa-envelope"></i> donthaveany@abc.com </p>
               <p> <i class="fas fa-map-marker-alt"></i> Ambala,Haryana, India - 133001 </p>
            </div>

            <div class="box" style="text-align:left;border:none; background-color:inherit;box-shadow:none;">
               <h3>Follow Us</h3>
               <a href="#"> <i class="fab fa-facebook-f"></i> Facebook </a>
               <a href="#"> <i class="fab fa-twitter"></i> Twitter </a>
               <a href="#"> <i class="fab fa-instagram"></i> Instagram </a>
               <a href="#"> <i class="fab fa-linkedin"></i> Linkedin </a>
            </div>

         </div>

         <p class="credit"> &copy; copyright @ by <span>Mr. Ayush</span> </p>

      </section>
      <!-- Footer-End -->
</body>

</html>