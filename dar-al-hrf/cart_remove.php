<?php
// Start the session
session_start();

// Get the data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Ensure the ID is cleanly checked
$id = isset($data['id']) ? $data['id'] : null;

// If cart doesn't exist yet, create empty one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Create a new empty cart
$newCart = array();

if ($id !== null) {
    // Loop through the cart and keep everything EXCEPT the item we want to remove
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        // Loose comparison (==) handles string vs integer IDs seamlessly
        if ($_SESSION['cart'][$i]['id'] != $id) {
            $newCart[] = $_SESSION['cart'][$i];
        }
    }
}

// Replace the old cart with the new one
$_SESSION['cart'] = $newCart;

// ==========================================
// CRITICAL ADDITION: CALCULATE FRESH TOTALS
// ==========================================
$productsSubtotal = 0;
$workshopsSubtotal = 0;

foreach ($_SESSION['cart'] as $item) {
    $rowValue = (float)$item['price'] * (int)$item['qty'];
    if (isset($item['type']) && $item['type'] === 'workshop') {
        $workshopsSubtotal += $rowValue;
    } else {
        $productsSubtotal += $rowValue;
    }
}

$serviceFee = 15;
$grandTotal = $productsSubtotal + $workshopsSubtotal + $serviceFee;

// Send back success message ALONG with fresh data
header('Content-Type: application/json');
echo json_encode(array(
    'success' => true,
    'productsSubtotal' => number_format($productsSubtotal, 2),
    'workshopsSubtotal' => number_format($workshopsSubtotal, 2),
    'grandTotal' => number_format($grandTotal, 2)
));
exit;
?>