<?php
// Start the session
session_start();

// Get the data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Ensure data exists
$id     = isset($data['id']) ? (int)$data['id'] : null;
$qty    = isset($data['qty']) ? (int)$data['qty'] : null;
$action = isset($data['action']) ? $data['action'] : '';

// If cart doesn't exist yet, create an empty one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Track if we actually altered anything so we know if we need to re-index
$cartChanged = false;

// Handle the Global Clear All action first
if ($action === 'clear_all') {
    $_SESSION['cart'] = [];
    $cartChanged = true;
} 
// Handle individual item operations
elseif ($id !== null) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $id) {
            
            // Explicit delete action OR quantity set to 0/negative
            if ($action === 'delete' || ($qty !== null && $qty <= 0)) {
                unset($_SESSION['cart'][$key]);
                $cartChanged = true;
            } 
            // Normal quantity update operation
            elseif ($qty !== null) {
                $_SESSION['cart'][$key]['qty'] = $qty;
                $cartChanged = true;
            }
            
            break; // Stop looking once the target item is found and handled
        }
    }
}

// SAFE ZONE: Re-index the array keys ONLY after the loop has fully terminated
if ($cartChanged && !empty($_SESSION['cart'])) {
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Send back success message along with calculated sub-totals for checkout.php to display
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

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'productsSubtotal' => number_format($productsSubtotal, 2),
    'workshopsSubtotal' => number_format($workshopsSubtotal, 2),
    'grandTotal' => number_format($grandTotal, 2)
]);
exit;
?>