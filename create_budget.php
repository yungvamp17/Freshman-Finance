<?php
session_start();
@include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['budgetName'], $_POST['budgetAmount'], $_POST['budgetGoal'])) {
        $budgetName = mysqli_real_escape_string($conn, $_POST['budgetName']);
        $budgetAmount = mysqli_real_escape_string($conn, $_POST['budgetAmount']);
        $budgetGoal = mysqli_real_escape_string($conn, $_POST['budgetGoal']);
        $user_id = $_SESSION['user_id']; // Assuming you have user_id stored in session

        // Insert new budget into the database
        $insertBudget = "INSERT INTO budgets (user_id, budget_name, amount, goal) VALUES ('$user_id', '$budgetName', '$budgetAmount', '$budgetGoal')";
        $result = mysqli_query($conn, $insertBudget);
        
        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        } else {
            // Get the last inserted budget ID
            $lastInsertedId = mysqli_insert_id($conn);

            // Redirect to add_expense.php and pass the budget ID as a parameter
            header("Location: add_expense.php?budget_id=$lastInsertedId");
            exit();
        }
    } else {
        // Handle missing data or errors
        // Redirect or show error messages
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (other meta tags) ... -->
    <title>Budget Page</title>
    <link rel="stylesheet" href="finance.css">
</head>
<body background="background.jpg">
    <!-- ... (header and user information) ... -->

    <div class="container">
        <div class="form-container">
            <form action="" method="post">
                <h2>Create Budget</h2>
                <h3>Budget Name</h3>
                <input type="text" name="budgetName" required placeholder="e.g Transport">
                <h3>Amount</h3>
                <input type="number" name="budgetAmount" required placeholder="e.g 500">
                <h3>Financial Goal</h3>
                <input type="number" name="budgetGoal" required placeholder="Enter your financial goal">
                <input type="submit" value="Create Budget" class="form-btn">
            </form>

            <!-- Button to navigate to Add Expense without creating a budget -->
            <form action="add_expense.php" method="get">
                <input type="submit" value="Add Expense Without Creating Budget" class="form-btn">
            </form>
        </div>
    </div>
</body>
</html>
