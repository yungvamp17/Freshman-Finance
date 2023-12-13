<?php
session_start();
@include 'config.php';

$faId = $_SESSION['fa_id'];

// Fetch financial advisor data from the database
$getFA = "SELECT * FROM user_form WHERE ID = '$faId'";
$faResult = mysqli_query($conn, $getFA);
$faData = mysqli_fetch_assoc($faResult);

// Update financial advisor username and password if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_fa'])) {
    if (isset($_POST['new_username'], $_POST['new_password'])) {
        $newUsername = mysqli_real_escape_string($conn, $_POST['new_username']);
        $newRawPassword = mysqli_real_escape_string($conn, $_POST['new_password']); // Get the raw password

        // Hash the password
        $newPassword = password_hash($newRawPassword, PASSWORD_DEFAULT);

        // Update financial advisor details in the database
        $updateFADetails = "UPDATE user_form SET name = '$newUsername', Password = '$newPassword' WHERE ID = '$faId'";
        $result = mysqli_query($conn, $updateFADetails);

        if ($result) {
            // Update session with new username
            $_SESSION['fa_name'] = $newUsername;
            // Add success message if needed
        } else {
            // Handle error while updating financial advisor details
            // Add error message if needed
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Advisor Account</title>
    <link rel="stylesheet" href="finance.css">
</head>
<body background="background.jpg">
    <section>
        <!-- Header -->
        <a href="#"><img src="5.3.png" alt="Logo" width="300px"></a>
        <header>
            <a href="login_form.php" class="logout-btn">Log Out</a>
            <a href="fa_page.php" class="logout-btn">Home</a>
        </header>
    </section>

    <div class="user">
        <!-- User information -->
        <h1>Welcome Back, <span><?php echo $_SESSION['fa_name']; ?></span></h1>
    </div>
    <div class="container">
        <!-- Update Financial Advisor Details -->
        <div class="form-container">
            <form action="" method="post">
                <h2>Update Financial Advisor Details</h2>
                <input type="text" id="new_username" name="new_username" required placeholder="Enter a new Username"><br><br>
                <input type="password" id="new_password" name="new_password" required placeholder="Enter a new Password"><br><br>
                <input type="submit" name="update_fa" value="Update Details" class="form-btn">
            </form>
        </div>
    </div>
</body>
</html>
