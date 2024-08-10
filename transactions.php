<?php
include('includes/header.php');
include('includes/db_connect.php');

$products = $pdo->query("SELECT * FROM products")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $transaction_type = $_POST['transaction_type'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $new_quantity = $transaction_type === 'purchase' ? $product['quantity'] + $quantity : $product['quantity'] - $quantity;

        if ($new_quantity < 0) {
            echo "<p>Insufficient stock for sale.</p>";
        } else {
            $stmt = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_quantity, $product_id]);

            $stmt = $pdo->prepare("INSERT INTO transactions (product_id, quantity, transaction_type, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_id, $quantity, $transaction_type, $product['price']]);

            echo "<p>Transaction completed successfully!</p>";
        }
    } else {
        echo "<p>Product not found.</p>";
    }
}
?>

<main>
    <h2>Product Transactions</h2>
    <form action="transactions.php" method="post">
        <label for="product_id">Select Product:</label>
        <select name="product_id" id="product_id" required>
            <?php foreach ($products as $product): ?>
                <option value="<?php echo $product['id']; ?>">
                    <?php echo htmlspecialchars($product['name']) . " - $" . number_format($product['price'], 2); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" required>

        <label for="transaction_type">Transaction Type:</label>
        <select name="transaction_type" id="transaction_type" required>
            <option value="purchase">Purchase</option>
            <option value="sale">Sale</option>
        </select>

        <button type="submit">Submit Transaction</button>
    </form>
</main>

<?php include('includes/footer.php'); ?>