<?php
// Start the session
session_start();

// Get the data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Get the product id and new quantity
$id  = $data['id'];
$qty = $data['qty'];

// If cart doesn't exist yet, create empty one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Loop through the cart and find the item we want to update
for ($i = 0; $i < count($_SESSION['cart']); $i++) {
    if ($_SESSION['cart'][$i]['id'] == $id) {
        // Found it! Update the quantity
        $_SESSION['cart'][$i]['qty'] = $qty;
        break;
    }
}

// Send back success message
echo json_encode(array('success' => true));
?>