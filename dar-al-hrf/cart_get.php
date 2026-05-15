<?php
// Start the session
session_start();

// If cart doesn't exist yet, create empty one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Send the cart contents back to JavaScript
echo json_encode(array('success' => true, 'cart' => $_SESSION['cart']));
?>