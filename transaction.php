<?php
include('includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    $transaction_type = $_POST['transaction_type'];

    $stmt = $pdo->prepare("SELECT price, quantity FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo "Product not found.";
        exit();
    }

    if ($transaction_type == 'sale' && $quantity > $product['quantity']) {
        echo "Not enough stock available for sale.";
        exit();
    }

    $new_quantity = $transaction_type == 'sale' ? $product['quantity'] - $quantity : $product['quantity'] + $quantity;

    $stmt = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
    $stmt->execute([$new_quantity, $product_id]);

    $stmt = $pdo->prepare("INSERT INTO transactions (product_id, transaction_type, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$product_id, $transaction_type, $quantity, $product['price']]);

    echo ucfirst($transaction_type) . " completed successfully.";
}
