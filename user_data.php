<?php
session_start();
@include 'config.php';

// Check if the user is logged in as a financial advisor
$isAdvisor = isset($_SESSION['fa_name']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_user'], $_POST['support_text'])) {
    $selectedUserId = mysqli_real_escape_string($conn, $_POST['selected_user']);
    $supportText = mysqli_real_escape_string($conn, $_POST['support_text']);
    $advisorId = $_SESSION['fa_id']; // Assuming the advisor's ID is stored in the session

    // Insert the provided support into the advisor_feedback table
    $insertSupportQuery = "INSERT INTO advisor_feedback (user_id, advisor_id, feedback_text) VALUES ('$selectedUserId', '$advisorId', '$supportText')";
    $insertSupportResult = mysqli_query($conn, $insertSupportQuery);

    if ($insertSupportResult) {
        // Feedback stored successfully
        // Redirect back to user_data.php or perform further actions
        header("Location: user_data.php?user_id=$selectedUserId");
        exit();
    } else {
        // Handle insertion failure
        echo "Support submission failed.";
    }
}

// Fetch selected user's data from the database
if ($isAdvisor && isset($_GET['user_id'])) {
    $selectedUserId = mysqli_real_escape_string($conn, $_GET['user_id']);

    // Fetch user data
    $getUserData = "SELECT * FROM user_form WHERE ID = '$selectedUserId'";
    $userDataResult = mysqli_query($conn, $getUserData);

    // Fetch user's budget data
    $getUserBudgets = "SELECT * FROM budgets WHERE user_id = '$selectedUserId'";
    $userBudgetsResult = mysqli_query($conn, $getUserBudgets);

    if (!$userDataResult || !$userBudgetsResult) {
        // Handle query execution failure
        echo "Error: " . mysqli_error($conn);
        // Perform necessary error handling (logging, displaying an error message, etc.)
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
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
            <form action="user_data.php" method="post">
                <?php if ($isAdvisor && isset($userDataResult) && $userDataResult instanceof mysqli_result && mysqli_num_rows($userDataResult) > 0) : ?>
                    <?php $userData = mysqli_fetch_assoc($userDataResult); ?>
                    <h2>User Data</h2>
                    <p><strong>User ID:</strong> <?php echo $userData['ID']; ?></p>
                    <p><strong>Name:</strong> <?php echo $userData['name']; ?></p>
                    <!-- Display more user data as needed -->

                    <h2>User's Budget Data</h2>
                    <?php if ($userBudgetsResult && mysqli_num_rows($userBudgetsResult) > 0) : ?>
                        <table>
                            <tr>
                                <th>Budget Name</th>
                                <th>Amount</th>
                                <th>Spent</th>
                                <!-- Add more columns if needed -->
                            </tr>
                            <?php while ($budgetData = mysqli_fetch_assoc($userBudgetsResult)) : ?>
                                <tr>
                                    <td><?php echo $budgetData['budget_name']; ?></td>
                                    <td><?php echo $budgetData['amount']; ?></td>
                                    <td><?php echo $budgetData['spent']; ?></td>
                                    <!-- Display additional budget data -->
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else : ?>
                        <p>No budget data available for this user.</p>
                    <?php endif; ?>

                    <h2>Provide Support/Analysis</h2>
                    <input type="hidden" name="selected_user" value="<?php echo $selectedUserId; ?>">
                    <textarea name="support_text" rows="4" cols="50" placeholder="Enter support text"></textarea>
                    <input type="submit" value="Submit Support" class="form-btn">
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
