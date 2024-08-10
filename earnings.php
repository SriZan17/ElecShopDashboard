<?php
include('includes/header.php');
include('includes/db_connect.php');

if ($_SESSION['role'] != 'manager') {
    header("Location: unauthorized.php");
    exit();
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'day';
$dateQuery = "";

switch ($filter) {
    case 'day':
        $dateQuery = "DATE(transaction_date) = CURDATE()";
        break;
    case 'month':
        $dateQuery = "MONTH(transaction_date) = MONTH(CURDATE()) AND YEAR(transaction_date) = YEAR(CURDATE())";
        break;
    case 'year':
        $dateQuery = "YEAR(transaction_date) = YEAR(CURDATE())";
        break;
}

$purchases = $pdo->query("SELECT SUM(quantity * price) AS total FROM transactions WHERE transaction_type = 'purchase' AND $dateQuery")->fetchColumn();
$sales = $pdo->query("SELECT SUM(quantity * price) AS total FROM transactions WHERE transaction_type = 'sale' AND $dateQuery")->fetchColumn();
$earnings = $sales - $purchases;

?>

<main>
    <h2 style="text-align: center; font-size: 28px; font-weight: 700; color: #2e2e2e;">Earnings Overview</h2>

    <div class="earnings-filter">
        <label for="filter" style="font-weight: bold; margin-right: 10px;">Select Period:</label>
        <form action="earnings.php" method="get">
            <select name="filter" id="filter" onchange="this.form.submit()">
                <option value="day" <?php if ($filter == 'day') echo 'selected'; ?>>Today</option>
                <option value="month" <?php if ($filter == 'month') echo 'selected'; ?>>This Month</option>
                <option value="year" <?php if ($filter == 'year') echo 'selected'; ?>>This Year</option>
            </select>
        </form>
    </div>

    <div style="display: flex; justify-content: space-around; text-align: center;">
        <div style="background-color: #4CAF50; color: white; padding: 20px; border-radius: 10px; width: 30%;">
            <p>Total Purchases</p>
            <h3>Rs <?php echo number_format($purchases, 2); ?></h3>
        </div>
        <div style="background-color: #2196F3; color: white; padding: 20px; border-radius: 10px; width: 30%;">
            <p>Total Sales</p>
            <h3>Rs <?php echo number_format($sales, 2); ?></h3>
        </div>
        <div style="background-color: #FFC107; color: white; padding: 20px; border-radius: 10px; width: 30%;">
            <p>Net Earnings</p>
            <h3>Rs <?php echo number_format($earnings, 2); ?></h3>

        </div>
    </div>
</main>

<footer>
    <p>&copy; 2024 Shop Management Dashboard. All rights reserved.</p>
</footer>
</body>

</html>