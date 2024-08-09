<?php
include('includes/header.php');
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = '';

    // Validate the uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        $max_size = 2 * 1024 * 1024; // 2MB size limit

        if (in_array($file_type, $allowed_types) && $_FILES['image']['size'] <= $max_size) {
            $image = 'uploads/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        } else {
            $error = "Invalid file type or size. Please upload a valid image.";
        }
    }


    $stmt = $pdo->prepare("INSERT INTO products (name, price, image, quantity) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $image, $quantity]);
}

$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<main>
    <h2>Products</h2>
    <form action="products.php" method="post" enctype="multipart/form-data">
        <label for="name">Product Name</label>
        <input type="text" name="name" id="name" required>

        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" id="price" required>

        <label for="image">Image</label>
        <input type="file" name="image" id="image">

        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" required>

        <button type="submit">Add Product</button>
    </form>

    <h3>Product List</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" width="50"></td>
                <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                <td>
                    <button onclick="handleTransaction(<?php echo $product['id']; ?>, 'purchase')">Buy</button>
                    <button onclick="handleTransaction(<?php echo $product['id']; ?>, 'sale')">Sell</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
</body>

</html>
<?php include('includes/footer.php'); ?>