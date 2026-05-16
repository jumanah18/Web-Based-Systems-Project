<?php
// Start the session
session_start();

// 1. REQUIRE DB CONNECTION TO CHECK STOCK
require 'db.php'; 

// Get the data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    exit;
}

// Extract data variables
$id         = (int)$data['id'];
$chosenQty  = isset($data['qty']) ? (int)$data['qty'] : 1;

// Ensure quantity added is at least 1
if ($chosenQty < 1) {
    echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1.']);
    exit;
}

// 2. BACKEND STOCK CHECK: Fetch available quantity from DB
$stmt = $pdo->prepare("SELECT quantity FROM products WHERE pid = ? AND category != 'Workshop'");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

$dbStock = (int)$product['quantity'];

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 3. CALCULATION: Check what will be the NEW total quantity in the cart
$alreadyInCart = false;
$targetQty = $chosenQty; // Default if it's a new item

for ($i = 0; $i < count($_SESSION['cart']); $i++) {
    if ($_SESSION['cart'][$i]['id'] == $id) {
        $alreadyInCart = true;
        // Calculate what the new quantity would be if we allowed it
        $targetQty = $_SESSION['cart'][$i]['qty'] + $chosenQty;
        
        // 4. VALIDATE AGAINST STOCK
        if ($targetQty > $dbStock) {
            echo json_encode([
                'success' => false, 
                'message' => "Cannot add more. You already have " . $_SESSION['cart'][$i]['qty'] . " in your cart, and maximum available stock is " . $dbStock . "."
            ]);
            exit;
        }
        
        // If it passes stock check, apply the user's full chosen quantity
        $_SESSION['cart'][$i]['qty'] = $targetQty;
        break;
    }
}

// If product was not in cart, check stock for new item and add it
if (!$alreadyInCart) {
    if ($targetQty > $dbStock) {
        echo json_encode(['success' => false, 'message' => "Requested quantity exceeds available stock ($dbStock left)."]);
        exit;
    }

    $newItem = array(
        'id'      => $id,
        'name'    => $data['name'],
        'price'   => (float)$data['price'],
        'image'   => $data['image'],
        'artisan' => $data['artisan'],
        'type'    => $data['type'],
        'qty'     => $chosenQty // Correctly honors the selected quantity
    );
    $_SESSION['cart'][] = $newItem;
}

// Send back success message
echo json_encode(array('success' => true));
?>