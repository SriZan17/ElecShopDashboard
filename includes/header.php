<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
    <title>Shop Management Dashboard</title>
</head>

<body>
    <header>
        <h1>Shop Management Dashboard</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="transactions.php">Transactions</a>
            <a href="inventory.php">Inventory</a>
            <a href="products.php">Add Product</a>
            <?php if ($_SESSION['role'] == 'manager'): ?>
                <a href="earnings.php">Earnings</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </nav>
    </header>