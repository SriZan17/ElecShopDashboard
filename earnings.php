<?php
include('includes/header.php');
include('includes/db_connect.php');

if ($_SESSION['role'] != 'manager') {
    header("Location: unauthorized.php");
    exit();
}

$purchases = $pdo->query("SELECT SUM(quantity * price) AS total FROM transactions WHERE transaction_type = 'purchase'")->fetchColumn();
$sales = $pdo->query("SELECT SUM(quantity * price) AS total FROM transactions WHERE transaction_type = 'sale'")->fetchColumn();
$earnings = $sales - $purchases;
?>

<main>
    <h2>Earnings</h2>
    <p>Total Purchases: $<?php echo htmlspecialchars(number_format($purchases, 2)); ?></p>
    <p>Total Sales: $<?php echo htmlspecialchars(number_format($sales, 2)); ?></p>
    <p><strong>Net Earnings: $<?php echo htmlspecialchars(number_format($earnings, 2)); ?></strong></p>
</main>
</body>

</html>