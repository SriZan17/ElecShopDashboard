<?php
include('includes/header.php');
include('includes/db_connect.php');

$products = $pdo->query("SELECT * FROM products")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $transaction_type = $_POST['transaction_type'];
    $price = $_POST['price'];

    // Fetch the product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $new_quantity = $transaction_type === 'purchase' ? $product['quantity'] + $quantity : $product['quantity'] - $quantity;

        if ($new_quantity < 0) {
            echo "<p>Insufficient stock for sale.</p>";
        } else {
            // Update the product quantity
            $stmt = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
            $stmt->execute([$new_quantity, $product_id]);

            // Insert the transaction record
            $stmt = $pdo->prepare("INSERT INTO transactions (product_id, quantity, transaction_type, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_id, $quantity, $transaction_type, $price]);

            // If it's a purchase, update the average price
            if ($transaction_type === 'purchase') {
                $total_cost = ($product['price'] * $product['quantity']) + ($price * $quantity);
                $new_avg_price = $total_cost / $new_quantity;
                $stmt = $pdo->prepare("UPDATE products SET price = ? WHERE id = ?");
                $stmt->execute([$new_avg_price, $product_id]);
            }

            echo "<p>Transaction completed successfully!</p>";
        }
    } else {
        echo "<p>Product not found.</p>";
    }
}

$transactions = $pdo->query("SELECT transactions.*, products.name FROM transactions JOIN products ON transactions.product_id = products.id ORDER BY transactions.transaction_date DESC")->fetchAll();
?>

<main>
    <h2>Product Transactions</h2>

    <form action="transactions.php" method="post">
        <label for="product_id">Select Product:</label>
        <select name="product_id" id="product_id" required>
            <?php foreach ($products as $product): ?>
                <option value="<?php echo $product['id']; ?>">
                    <?php echo htmlspecialchars($product['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" required>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="transaction_type">Transaction Type:</label>
        <select name="transaction_type" id="transaction_type" required>
            <option value="purchase">Purchase</option>
            <option value="sale">Sale</option>
        </select>

        <button type="submit">Submit Transaction</button>
    </form>

    <h2>Transaction History</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Transaction Type</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['name']); ?></td>
                    <td><?php echo ucfirst($transaction['transaction_type']); ?></td>
                    <td><?php echo $transaction['quantity']; ?></td>
                    <td>Rs <?php echo number_format($transaction['price'], 2); ?></td>
                    <td>Rs <?php echo number_format($transaction['quantity'] * $transaction['price'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include('includes/footer.php'); ?>