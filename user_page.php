<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['user_name'])){
   header('location:login_form.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>user page</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body background ="background.jpg">
<div class="container">

   <div class="content">
      <h3>Hi, <span>User</span></h3>
      <h1>welcome <span><?php echo $_SESSION['user_name'] ?></span></h1>
      <p>This is an user page</p>
      <a href="userpf.php" class="btn">Profile</a>
      <a href="create_budget.php" class="btn">Finances</a>
      <a href="logout.php" class="btn">Logout</a>

   </div>

</div>

</body>
</html>