<!-- admin_acc.php -->
<?php
session_start();
@include 'config.php';

$adminId = $_SESSION['admin_id'];

// Fetch admin data from the database
$getAdmin = "SELECT * FROM user_form WHERE ID = '$adminId'";
$adminResult = mysqli_query($conn, $getAdmin);
$adminData = mysqli_fetch_assoc($adminResult);

// Update admin details if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_admin'])) {
    if (isset($_POST['new_username'], $_POST['new_password'])) {
        $newUsername = mysqli_real_escape_string($conn, $_POST['new_username']);
        $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);

        // Hash the password using password_hash() before updating
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update admin details in the database with the hashed password
        $updateUserDetails = "UPDATE user_form SET name = '$newUsername', Password = '$hashedPassword' WHERE ID = '$adminId'";
        $result = mysqli_query($conn, $updateUserDetails);

        if ($result) {
            // Update session with new username
            $_SESSION['admin_name'] = $newUsername;
            // Add success message if needed
        } else {
            // Handle error while updating user details
            echo "Error updating admin details: " . mysqli_error($conn);
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
    <title>Admin Account</title>
    <link rel="stylesheet" href="finance.css">
</head>
<body background="background.jpg">
    <section>
        <!-- Header -->
        <a href="#"><img src="5.3.png" alt="Logo" width="300px"></a>
        <header>
            <a href="login_form.php" class="logout-btn">Log Out</a>
            <a href="admin_page.php" class="logout-btn">Home</a>
        </header>
    </section>

    <div class="user">
        <!-- User information -->
        <h1>Welcome Back, <span><?php echo $_SESSION['admin_name']; ?></span></h1>
    </div>

    <div class="container">
        <!-- Update Admin Details -->
        <div class="form-container">
            <form action="" method="post">
                <h2>Update Admin Details</h2>
                <input type="text" id="new_username" name="new_username" required placeholder="Enter a new Username"><br><br>
                <input type="password" id="new_password" name="new_password" required placeholder="Enter a new Password"><br><br>
                <input type="submit" name="update_admin" value="Update Details" class="form-btn">
            </form>
        </div>
    </div>
</body>
</html>
