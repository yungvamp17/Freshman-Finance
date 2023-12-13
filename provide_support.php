<?php
session_start();
@include 'config.php';

// Check if the user is logged in as a financial advisor
$isAdvisor = isset($_SESSION['fa_name']);

// Fetch normal users with user_type 'user' from the database
if ($isAdvisor) {
    $getUsers = "SELECT * FROM user_form WHERE user_type = 'user'";
    $usersResult = mysqli_query($conn, $getUsers);

    if (!$usersResult) {
        // Handle query execution failure
        echo "Error: " . mysqli_error($conn);
        // Perform necessary error handling (logging, displaying an error message, etc.)
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_user'])) {
    $selectedUserId = mysqli_real_escape_string($conn, $_POST['selected_user']);
    
    // Redirect to user_data.php with the selected user's ID
    header("Location: user_data.php?user_id=$selectedUserId");
    exit();
} else {
    // Handle missing data or unauthorized access
    // Redirect or display an error message as needed
}
?>

<!-- Rest of the HTML remains unchanged -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select User</title>
    <link rel="stylesheet" href="finance.css">
</head>
<body background="background.jpg">
    <section>
        <!-- Header -->
        <a href="#"><img src="5.3.png" alt="Logo" width="300px"></a>
        <header>
            <a href="logout.php" class="logout-btn">Log Out</a>
            <a href="fa_page.php" class="logout-btn">Home</a>
        </header>
    </section>

    <div class="user">
        <!-- User information -->
        <h1>Welcome, Financial Advisor: <span><?php echo $_SESSION['fa_name']; ?></span></h1>
    </div>

    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <?php if ($isAdvisor && isset($usersResult) && $usersResult instanceof mysqli_result && mysqli_num_rows($usersResult) > 0) : ?>
                    <h3>Select User</h3>
                    <select name="selected_user">
                        <?php while ($user = mysqli_fetch_assoc($usersResult)) : ?>
                            <option value="<?php echo $user['ID']; ?>"><?php echo $user['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="submit" value="View User Data" class="form-btn">
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
