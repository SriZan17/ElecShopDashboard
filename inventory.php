<?php
include('includes/header.php');
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $stmt = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
    $stmt->execute([$quantity, $product_id]);
}

$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<main>
    <h2>Inventory Management</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Current Quantity</th>
            <th>Update Quantity</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                <td>
                    <form action="inventory.php" method="post" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
</body>

</html>
<?php include('includes/footer.php'); ?>