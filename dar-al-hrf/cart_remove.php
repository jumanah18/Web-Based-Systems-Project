<?php
// Start the session
session_start();

// Get the data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Get the product id we want to remove
$id = $data['id'];

// If cart doesn't exist yet, create empty one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Create a new empty cart
$newCart = array();

// Loop through the cart and keep everything EXCEPT the item we want to remove
for ($i = 0; $i < count($_SESSION['cart']); $i++) {
    if ($_SESSION['cart'][$i]['id'] != $id) {
        // This item is NOT the one we want to remove, so keep it
        $newCart[] = $_SESSION['cart'][$i];
    }
}

// Replace the old cart with the new one
$_SESSION['cart'] = $newCart;

// Send back success message
echo json_encode(array('success' => true));
?>