<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['admin_name'])){
   header('location:login_form.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body background="background.jpg">
   
<div class="container">
   <div class="content">
      <h3>Hello, <span>Admin</span></h3>
      <h1>welcome <span><?php echo $_SESSION['admin_name'] ?></span></h1>
      <p>This is an admin page</p>
      <a href="logout.php" class="btn">Logout</a>
      <a href="user_acc.php" class="btn">User Accounts</a>
      <a href="report.php" class="btn">Reports</a>
      <a href="admin_acc.php" class="btn">Profile</a>


   </div>

</div>

</body>
</html>