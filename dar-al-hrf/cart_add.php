<?php
// Start the session
session_start();

// Get the data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// If the cart doesn't exist yet, create it as an empty list
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get the product id from the data
$id = $data['id'];

// Check if this product is already in the cart
$alreadyInCart = false;

for ($i = 0; $i < count($_SESSION['cart']); $i++) {
    if ($_SESSION['cart'][$i]['id'] == $id) {
        // Product found, increase quantity by 1
        $_SESSION['cart'][$i]['qty']++;
        $alreadyInCart = true;
        break;
    }
}

// If product was not in cart, add it
if ($alreadyInCart == false) {
    $newItem = array(
        'id'      => $data['id'],
        'name'    => $data['name'],
        'price'   => $data['price'],
        'image'   => $data['image'],
        'artisan' => $data['artisan'],
        'type'    => $data['type'],
        'qty'     => 1
    );
    $_SESSION['cart'][] = $newItem;
}

// Send back success message
echo json_encode(array('success' => true));
?>