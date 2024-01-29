<?php
    session_start();
    @include 'config.php';

    // Check if the user has an existing budget
    $user_id = $_SESSION['user_id']; // Assuming you have user_id stored in session
    $checkExistingBudget = "SELECT COUNT(*) as count FROM budgets WHERE user_id = '$user_id'";
    $existingBudgetResult = mysqli_query($conn, $checkExistingBudget);

    $hasExistingBudget = 0;
    if ($existingBudgetResult && mysqli_num_rows($existingBudgetResult) > 0) {
      $row = mysqli_fetch_assoc($existingBudgetResult);
      $hasExistingBudget = $row['count'];
    }

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
        <div class="form-container">
            <?php if ($hasExistingBudget > 0): ?>
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
                    <input type="submit" value="Add Expense to Already Existing Budget" class="form-btn">
                </form>
            <?php else: ?>
                <!-- If the user does not have an existing budget, display the form to create a new budget -->
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
            <?php endif; ?>
        </div>
    </div>



    </body>
    </html>
