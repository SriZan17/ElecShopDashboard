<?php
include('includes/header.php');
include('includes/db_connect.php');

$products = $pdo->query("SELECT * FROM products")->fetchAll();

$total_inventory_value = 0;
?>

<main>
    <h2>Inventory Management</h2>
    <table>
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price (Avg. Cost)</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <?php
                $product_value = $product['quantity'] * $product['price'];
                $total_inventory_value += $product_value;
                ?>
                <tr>
                    <td>
                        <?php if (!empty($product['image'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <img src="uploads/default.png" alt="Default Image" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo $product['quantity']; ?></td>
                    <td>Rs <?php echo number_format($product['price'], 2); ?></td>
                    <td>Rs <?php echo number_format($product_value, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Total Inventory Value: Rs <?php echo number_format($total_inventory_value, 2); ?></h3>
</main>

<?php include('includes/footer.php'); ?>