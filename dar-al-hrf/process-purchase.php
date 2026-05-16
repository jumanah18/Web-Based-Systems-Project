<?php
// process-purchase.php
session_start();
require 'db.php';

// If the cart is empty, send them back to the checkout page
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: checkout.php');
    exit;
}

// Loop through each item sitting in the cart session array
foreach ($_SESSION['cart'] as $item) {
    
    // Identify the product or workshop ID
    $productId = 0;
    if (isset($item['pid'])) {
        $productId = (int)$item['pid'];
    } elseif (isset($item['id'])) {
        $productId = (int)$item['id'];
    }
    
    $qtyBought = isset($item['qty']) ? (int)$item['qty'] : 1;

    // If we have a valid ID, run a vanilla update query to subtract the stock/seats
    if ($productId > 0) {
        $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE pid = ?");
        $stmt->execute([$qtyBought, $productId]);
    }
}

// Clear out the cart session completely
$_SESSION['cart'] = [];

// Redirect the browser to your success page
header('Location: purchase-completed.php');
exit;
?>