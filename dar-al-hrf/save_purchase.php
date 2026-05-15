<?php
// Get cart data sent from JavaScript
$data  = json_decode(file_get_contents('php://input'), true);
$cart  = $data['cart'];

// Get existing purchases from cookie
$existing  = isset($_COOKIE['dh_purchases']) ? json_decode($_COOKIE['dh_purchases'], true) : [];

// Create new purchase
$purchase = array(
    'date'  => date('d/m/Y'),
    'items' => $cart
);

// Add to purchases list
$existing[] = $purchase;

// Save cookie for 30 days using PHP setcookie()
setcookie(
    'dh_purchases',
    json_encode($existing),
    time() + (30 * 24 * 60 * 60),
    '/',
    '',
    false,
    false
);

echo json_encode(array('success' => true));
?>