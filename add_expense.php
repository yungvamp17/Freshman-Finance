<?php
session_start();
@include 'config.php';

// Fetch budget details including financial goal
function fetchBudgetDetails($conn, $budgetId) {
    $budgetData = [];
    $getBudgetDetails = "SELECT budget_name, amount, spent, goal FROM budgets WHERE budget_id = $budgetId";
    $result = mysqli_query($conn, $getBudgetDetails);

    if ($result && mysqli_num_rows($result) > 0) {
        $budgetData = mysqli_fetch_assoc($result);
    }

    return $budgetData;
}

// Fetch and display budget details if a specific budget ID is provided
$selectedBudgetId = (isset($_GET['budget_id']) && is_numeric($_GET['budget_id'])) ? (int)$_GET['budget_id'] : 0;
$selectedBudget = fetchBudgetDetails($conn, $selectedBudgetId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['budgetName']) && isset($_POST['budgetAmount'])) {
        $budgetName = mysqli_real_escape_string($conn, $_POST['budgetName']);
        $budgetAmount = mysqli_real_escape_string($conn, $_POST['budgetAmount']);
        $user_id = $_SESSION['user_id']; // Assuming you have user_id stored in session

        // Insert new budget into the database
        $insertBudget = "INSERT INTO budgets (user_id, budget_name, amount) VALUES ('$user_id', '$budgetName', '$budgetAmount')";
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
    }

    // Handle adding an expense to a selected budget
    if (isset($_POST['expenseName'], $_POST['expenseAmount'], $_POST['selectedBudget'])) {
        $expenseName = mysqli_real_escape_string($conn, $_POST['expenseName']);
        $expenseAmount = mysqli_real_escape_string($conn, $_POST['expenseAmount']);
        $selectedBudgetId = mysqli_real_escape_string($conn, $_POST['selectedBudget']);

        // Insert the expense into the expenses table
        $insertExpense = "INSERT INTO expenses (budget_id, user_id, expense_name, amount) VALUES ('$selectedBudgetId', '{$_SESSION['user_id']}', '$expenseName', '$expenseAmount')";
        $result = mysqli_query($conn, $insertExpense);

        if (!$result) {
            die('Error: ' . mysqli_error($conn)); // Output any database errors
        }

        // Update 'spent' field in the budgets table after adding an expense
        $getTotalSpent = "SELECT SUM(amount) AS total_spent FROM expenses WHERE budget_id = '$selectedBudgetId'";
        $totalSpentResult = mysqli_query($conn, $getTotalSpent);

        if ($totalSpentResult && mysqli_num_rows($totalSpentResult) > 0) {
            $row = mysqli_fetch_assoc($totalSpentResult);
            $totalSpent = (float) $row['total_spent'];

            // Update 'spent' field in the budgets table with the new total spent amount
            $updateSpentQuery = "UPDATE budgets SET spent = '$totalSpent' WHERE budget_id = '$selectedBudgetId'";
            $updateResult = mysqli_query($conn, $updateSpentQuery);

            if (!$updateResult) {
                die('Error updating spent amount: ' . mysqli_error($conn));
            }
        }

        // Fetch the updated budget details for the selected budget after adding an expense
        $selectedBudget = fetchBudgetDetails($conn, $selectedBudgetId);

        // Calculate new amount spent and remaining budget for the selected budget
        $remainingBudget = $selectedBudget['amount'] - $selectedBudget['spent'];
    }
}

// Fetch all budgets for the dropdown list
$getBudgets = "SELECT * FROM budgets";
$budgetsResult = mysqli_query($conn, $getBudgets);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
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
        <!-- Budget creation form -->
        <div class="form-container">
            <form action="" method="post">
                <h2>Create Budget</h2>
                <h3>Budget Name</h3>
                <input type="text" name="budgetName" required placeholder="e.g Transport">
                <h3>Amount</h3>
                <input type="number" name="budgetAmount" required placeholder="e.g 500">
                <input type="submit" value="Create Budget" class="form-btn">
            </form>
        </div>

        <!-- Expense addition form -->
        <div class="form-container">
            <form action="" method="post">
                <h2>Add Expense</h2>
                <?php
                    if (isset($budgetsResult) && mysqli_num_rows($budgetsResult) > 0) {
                        echo '<select name="selectedBudget">';
                        while ($row = mysqli_fetch_assoc($budgetsResult)) {
                            echo '<option value="' . $row['budget_id'] . '">' . $row['budget_name'] . '</option>';
                        }
                        echo '</select>';
                    }
                ?>
                <h3>Expense Name</h3>
                <input type="text" name="expenseName" required placeholder="e.g Fuel">
                <h3>Amount</h3>
                <input type="number" name="expenseAmount" required placeholder="e.g 200">
                <input type="submit" value="Create Expense" class="form-btn">
            </form>
        </div>

        <!-- Budget details -->
        <div class="ebudgets">
            <h1>Selected Budget: <?php echo isset($selectedBudget['budget_name']) ? $selectedBudget['budget_name'] : 'No budget selected'; ?></h1>
            <h2>Budget Amount: <?php echo isset($selectedBudget['amount']) ? $selectedBudget['amount'] : 'N/A'; ?></h2>
            <h2>Amount Spent: <?php echo isset($selectedBudget['spent']) ? $selectedBudget['spent'] : 'N/A'; ?></h2>
            <h2>Remaining Budget: <?php echo isset($remainingBudget) ? $remainingBudget : 'N/A'; ?></h2>
            <h2>Financial Goal: <?php echo isset($selectedBudget['goal']) ? $selectedBudget['goal'] : 'N/A'; ?></h2>
        </div>
    </div>
</body>
</html>
