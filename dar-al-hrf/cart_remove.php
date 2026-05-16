<?php
// cart_remove.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No request data received.']);
    exit;
}

// Read incoming key reference
$cartKey = isset($data['cart_key']) ? trim((string)$data['cart_key']) : '';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Your cart session is completely empty.']);
    exit;
}

// --- STRATEGY 1: Strict Direct Matching ---
if ($cartKey !== '' && isset($_SESSION['cart'][$cartKey])) {
    unset($_SESSION['cart'][$cartKey]);
    echo json_encode(['success' => true]);
    exit;
}

// --- STRATEGY 2: Numerical Fallback Selector (Fixes the "0" problem) ---
// If Javascript sent a position number (like 0, 1, 2), match it to the array's positions
if (is_numeric($cartKey)) {
    $numericTargetIndex = (int)$cartKey;
    $currentKeys = array_keys($_SESSION['cart']);
    
    if (isset($currentKeys[$numericTargetIndex])) {
        $actualSessionKeyName = $currentKeys[$numericTargetIndex];
        unset($_SESSION['cart'][$actualSessionKeyName]);
        echo json_encode(['success' => true]);
        exit;
    }
}

// --- STRATEGY 3: Smart Product Matcher ---
// If the key is a plain ID number like 12, look for a matching 'pid' or 'id' inside the cart rows
foreach ($_SESSION['cart'] as $actualKeyName => $item) {
    $itemPid = isset($item['pid']) ? (string)$item['pid'] : '';
    $itemId = isset($item['id']) ? (string)$item['id'] : '';
    
    if (($cartKey !== '' && $itemPid === $cartKey) || ($cartKey !== '' && $itemId === $cartKey)) {
        unset($_SESSION['cart'][$actualKeyName]);
        echo json_encode(['success' => true]);
        exit;
    }
}

// Final fallback error notice if all matching parameters fail
echo json_encode([
    'success' => false, 
    'message' => 'Could not find item match in session storage. Tried key identifier: ' . $cartKey
]);
exit;