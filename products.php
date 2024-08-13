<?php
include('includes/header.php');
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $image = $_FILES['image']['name'] ?? null;
    $target = null;

    if (!empty($image)) {
        $target = "uploads/" . basename($image);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $target = "uploads/default.png";
        }
    } else {
        $target = "uploads/default.png";
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, image) VALUES (?, ?)");
    $stmt->execute([$name, $target]);

    echo "<p>Product added successfully!</p>";
}
?>

<main>
    <h2>Add Product</h2>
    <form action="products.php" method="post" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="image">Product Image (optional):</label>
        <input type="file" name="image" id="image">

        <button type="submit">Add Product</button>
    </form>
</main>

<?php include('includes/footer.php'); ?>