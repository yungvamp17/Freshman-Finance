<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = $_POST['password']; // Don't hash the password here for comparison

   $select = "SELECT * FROM user_form WHERE email = '$email'";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $row = mysqli_fetch_array($result);

      $hashedPassword = $row['Password']; // Fetch hashed password from the database

      // Verify the entered password against the stored hash
      if(password_verify($password, $hashedPassword)){

         if($row['user_type'] == 'admin'){
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_id'] = $row['ID'];
            header('location: admin_page.php');
         } elseif($row['user_type'] == 'user'){
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_id'] = $row['ID']; 
            header('location: user_page.php');
         } elseif ($row['user_type'] == 'fa') {
            $_SESSION['fa_name'] = $row['name'];
            $_SESSION['fa_id'] = $row['ID'];
            header('location: fa_page.php');
         }
      } else {
         $error[] = 'Incorrect email or password!';
      }
   } else {
      $error[] = 'Incorrect email or password!';
   }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body background ="background.jpg">
   
<div class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="submit" name="submit" value="login now" class="form-btn">
      <p>don't have an account? <a href="register_form.php">register now</a></p>
      <p>Go back <a href="home.php">home</a></p>
   </form>

</div>

</body>
</html>