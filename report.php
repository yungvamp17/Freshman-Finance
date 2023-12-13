<!-- report_page.php -->
<?php
session_start();
@include 'config.php';

$isAdmin = true; // Assuming the user is an admin

if ($isAdmin) {
    $getUsers = "SELECT * FROM user_form WHERE user_type = 'user'";
    $usersResult = mysqli_query($conn, $getUsers);

    if (!$usersResult) {
        // Handle query execution failure
        echo "Error: " . mysqli_error($conn);
        // Perform necessary error handling (logging, displaying an error message, etc.)
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    $selectedUserId = mysqli_real_escape_string($conn, $_POST['selected_user']);

    $getUserFinancialData = "SELECT b.budget_id, b.budget_name, b.amount, b.spent
                             FROM budgets b
                             WHERE b.user_id = '$selectedUserId'";
    $financialDataResult = mysqli_query($conn, $getUserFinancialData);

    if (!$financialDataResult) {
        // Handle query execution failure for fetching financial data
        echo "Error: " . mysqli_error($conn);
        // Perform necessary error handling (logging, displaying an error message, etc.)
    } else {
        $reportContent = '';

        if (mysqli_num_rows($financialDataResult) > 0) {
            $reportContent .= "User Financial Report\n\n";
            $reportContent .= "Budget ID | Budget Name | Amount | Spent\n";

            while ($row = mysqli_fetch_assoc($financialDataResult)) {
                $reportContent .= $row['budget_id'] . " | " . $row['budget_name'] . " | " . $row['amount'] . " | " . $row['spent'] . "\n";
                // You can format the content according to your requirements
            }

            $file = 'user_report_' . date('Ymd') . '.txt';
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            echo $reportContent;
            exit();
        } else {
            echo "No financial data found for the selected user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<<head>
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
        <!-- Display normal users and generate report -->
        <div class="form-container">
            <h3>Generate User Report</h3>
            <form action="" method="post">
                <?php if ($isAdmin && isset($usersResult) && $usersResult instanceof mysqli_result && mysqli_num_rows($usersResult) > 0) : ?>
                    <label for="selected_user">Select User:</label>
                    <select name="selected_user" id="selected_user">
                        <?php while ($user = mysqli_fetch_assoc($usersResult)) : ?>
                            <option value="<?php echo $user['ID']; ?>"><?php echo $user['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="submit" name="generate_report" value="Generate Report" class="form-btn">
                <?php elseif ($isAdmin && isset($usersResult) && mysqli_num_rows($usersResult) === 0) : ?>
                    <p>No normal users found.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
