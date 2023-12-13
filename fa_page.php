<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['fa_name'])){
   header('location:login_form.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>financial advisor page</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body background="background.jpg">
<div class="container">

   <div class="content">
      <h3>Hi, <span>Financial Advisor</span></h3>
      <h1>welcome <span><?php echo $_SESSION['fa_name'] ?></span></h1>
      <p>This is an financial advisor page</p>
      <a href="logout.php" class="btn">Logout</a>
      <a href="provide_support.php" class="btn">Provide support</a>
      <a href="fa_acc.php" class="btn">Profile</a>

   </div>

</div>

</body>
</html>