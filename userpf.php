<?php
session_start();
@include 'config.php';

// Fetch user's budgets from the database
$user_id = $_SESSION['user_id'];
$getBudgets = "SELECT * FROM budgets WHERE user_id = '$user_id'";
$budgetsResult = mysqli_query($conn, $getBudgets);

// Function to fetch budget details by ID
function fetchBudgetDetails($conn, $budgetId) {
    $budgetData = [];
    $getBudgetDetails = "SELECT budget_name, amount, spent FROM budgets WHERE budget_id = $budgetId";
    $result = mysqli_query($conn, $getBudgetDetails);

    if ($result && mysqli_num_rows($result) > 0) {
        $budgetData = mysqli_fetch_assoc($result);
    }

    return $budgetData;
}

// Update user details if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    if (isset($_POST['new_username'], $_POST['new_password'])) {
        $newUsername = mysqli_real_escape_string($conn, $_POST['new_username']);
        $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);

        // Hash the password using password_hash() before updating
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update user details in the database with the hashed password
        $updateUserDetails = "UPDATE user_form SET name = '$newUsername', Password = '$hashedPassword' WHERE ID = '$user_id'";
        $result = mysqli_query($conn, $updateUserDetails);

        if ($result) {
            // Update session with new username
            $_SESSION['user_name'] = $newUsername;
            // Add success message if needed
        } else {
            // Handle error while updating user details
            echo "Error updating user details: " . mysqli_error($conn);
            // Add error message if needed
        }
    }
}

// Update budget amount if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'update_budget_') !== false) {
            $budgetId = str_replace('update_budget_', '', $key);
            $amountChange = mysqli_real_escape_string($conn, $_POST['amount_change_' . $budgetId]);

            // Fetch current amount
            $currentAmountQuery = "SELECT amount FROM budgets WHERE budget_id = '$budgetId'";
            $currentAmountResult = mysqli_query($conn, $currentAmountQuery);

            if ($currentAmountResult && mysqli_num_rows($currentAmountResult) > 0) {
                $row = mysqli_fetch_assoc($currentAmountResult);
                $currentAmount = $row['amount'];

                // Calculate new amount
                $newAmount = $currentAmount + $amountChange;

                // Update budget amount in the database
                $updateBudgetAmount = "UPDATE budgets SET amount = '$newAmount' WHERE budget_id = '$budgetId'";
                $result = mysqli_query($conn, $updateBudgetAmount);

                if (!$result) {
                    // Handle error while updating budget amount
                    echo "Error updating budget amount: " . mysqli_error($conn);
                    // Add error message if needed
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="finance.css">
</head>
<body background="background.jpg">
    <section>
        <!-- Header -->
        <a href="#"><img src="5.3.png" alt="Logo" width="300px"></a>
        <header>
            <a href="login_form.php" class="logout-btn">Log Out</a>
            <a href="user_page.php" class="logout-btn">Home</a>
        </header>
    </section>

    <div class="user">
        <!-- User information -->
        <h1>Welcome Back, <span><?php echo $_SESSION['user_name']; ?></span></h1>
    </div>

    <div class="container">
        <!-- Display Budgets -->
        <div class="form-container">
            <h2>User's Budgets</h2>
            <form action="" method="post">
                <?php if ($budgetsResult && mysqli_num_rows($budgetsResult) > 0) {
                    while ($row = mysqli_fetch_assoc($budgetsResult)) {
                        $budgetId = $row['budget_id'];
                        $budgetDetails = fetchBudgetDetails($conn, $budgetId);
                ?>
                        <div class="budget-container">
                            <h3><?php echo $budgetDetails['budget_name']; ?></h3>
                            <p>Amount: <?php echo $budgetDetails['amount']; ?></p>
                            <p>Spent: <?php echo $budgetDetails['spent']; ?></p>
                            <input type="number" name="amount_change_<?php echo $budgetId; ?>" placeholder="Amount Change">
                            <input type="submit" name="update_budget_<?php echo $budgetId; ?>" value="Update" class="form-btn">
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No budgets found for this user.</p>";
                }
                ?>
            </form>
        </div>

        <!-- Update User Details -->
        <div class="form-container">
            <form action="" method="post">
                <h2>Update Details</h2>
                <input type="text" id="new_username" name="new_username" required placeholder="Enter a new Username"><br><br>
                <input type="password" id="new_password" name="new_password" required placeholder="Enter a new Password"><br><br>
                <input type="submit" name="update_user" value="Update Details" class="form-btn">
            </form>
        </div>
    </div>
</body>
</html>
