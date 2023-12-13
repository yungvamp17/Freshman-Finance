<?php
session_start();
@include 'config.php';

$isAdmin = true; // Assuming the user is an admin

// Fetch user data from the database
if ($isAdmin) {
    $getUsers = "SELECT * FROM user_form";
    $usersResult = mysqli_query($conn, $getUsers);

    if (!$usersResult) {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete user if submitted
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userIdToDelete = mysqli_real_escape_string($conn, $_POST['delete_user']);

    $deleteUserQuery = "DELETE FROM user_form WHERE ID = '$userIdToDelete'";
    $deleteResult = mysqli_query($conn, $deleteUserQuery);

    if ($deleteResult) {
        // User deleted successfully
    } else {
        echo "Deleting of user unsuccessful";
    }
}

// Add new user or financial advisor
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $userType = mysqli_real_escape_string($conn, $_POST['user_type']);

    $insertUserQuery = "INSERT INTO user_form (name, Email, user_type) VALUES ('$newUsername', '$newEmail', '$userType')";
    $insertResult = mysqli_query($conn, $insertUserQuery);

    if ($insertResult) {
        // User added successfully
    } else {
        echo "Adding user unsuccessful";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
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
        <div class="form-container">   
            <!-- Display user data -->
            <form action="" method="post">
                <?php if ($isAdmin && isset($usersResult) && $usersResult instanceof mysqli_result && mysqli_num_rows($usersResult) > 0) : ?>
                    <h3>User Data</h3>
                    <?php if (isset($usersResult) && mysqli_num_rows($usersResult) > 0) : ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($user = mysqli_fetch_assoc($usersResult)) : ?>
                                    <tr>
                                        <td><?php echo $user['ID']; ?></td>
                                        <td><?php echo $user['name']; ?></td>
                                        <td><?php echo $user['Email']; ?></td>
                                        <td>
                                                <input type="hidden" name="delete_user" value="<?php echo $user['ID']; ?>">
                                                <input type="submit" value="Delete" class="form-btn">
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p>No users found.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="form-container">
            <!-- Add new user or financial advisor -->
            <h3>Add New User or Financial Advisor</h3>
            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
                <label for="user_type">User Type:</label>
                <select id="user_type" name="user_type">
                    <option value="user">User</option>
                    <option value="financial_advisor">Financial Advisor</option>
                </select>
                <input type="submit" value="Add User" name="add_user" class="form-btn">
            </form>
        </div>
    </div>
</body>
</html>
