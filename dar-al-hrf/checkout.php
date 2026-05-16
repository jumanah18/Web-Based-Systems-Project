<?php
session_start();
require 'db.php';

// Fallback if cart session isn't initialized
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// We will update the session quantities live if they exceed current stock
$cartUpdated = false;

if (!empty($cartItems)) {
    foreach ($cartItems as $index => $item) {
        // We only enforce inventory limits for standard products, not workshops
        if (isset($item['type']) && $item['type'] === 'product') {
            
            // Query the source of truth database for up-to-the-minute stock
            $stmt = $pdo->prepare("SELECT quantity FROM products WHERE pid = ?");
            $stmt->execute([(int)$item['id']]);
            $product = $stmt->fetch();
            
            if ($product) {
                $currentStock = (int)$product['quantity'];
                
                // If stock dropped or user manipulated the count, clamp it
                if ($item['qty'] > $currentStock) {
                    $_SESSION['cart'][$index]['qty'] = $currentStock;
                    $cartItems[$index]['qty'] = $currentStock;
                    $cartUpdated = true;
                }
                
                // Attach real stock to the item array so the HTML slider can read it
                $cartItems[$index]['maxStock'] = $currentStock;
            } else {
                // Product no longer exists in DB, drop it from cart
                unset($_SESSION['cart'][$index]);
                unset($cartItems[$index]);
                $cartUpdated = true;
            }
        } else {
            // Default max limit for workshops (e.g., remaining available seats)
            $cartItems[$index]['maxStock'] = 10; 
        }
    }
    
    // Re-index array if any items were dropped
    if ($cartUpdated) {
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        $cartItems = array_values($cartItems);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart &mdash; Dar Al Hiraf</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .qty-range-wrap { display: flex; align-items: center; gap: 10px; }
    .qty-range-wrap input[type="range"] {
      -webkit-appearance: none; appearance: none;
      width: 110px; height: 6px; border-radius: 3px;
      background: #d8d2c5; outline: none; cursor: pointer;
    }
    .qty-range-wrap input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none; appearance: none;
      width: 18px; height: 18px; border-radius: 50%;
      background: var(--green); cursor: pointer;
    }
    .qty-range-wrap input[type="range"]::-moz-range-thumb {
      width: 18px; height: 18px; border-radius: 50%;
      background: var(--green); cursor: pointer; border: none;
    }
    .qty-val { min-width: 28px; text-align: center; font-weight: 700; font-size: 15px; color: var(--green); }
    .row-price { font-weight: 700; color: var(--green); }
    .btn-delete {
      display: inline-flex; align-items: center; justify-content: center;
      width: 34px; height: 34px; font-size: 16px; color: #fff; cursor: pointer;
      border: none; border-radius: 50%; background: #c0392b;
      transition: background 0.18s, transform 0.15s; line-height: 1;
    }
    .btn-delete:hover { background: #96281b; transform: scale(1.1); }
    .sum-val { font-weight: 700; color: var(--green); }
    .sum-val-grand { font-weight: 700; font-size: 18px; color: var(--brown); }
    .empty-cart-msg { text-align: center; padding: 60px 20px; }
  </style>
</head>
<body>

<nav class="navbar">
  <div class="navbar-inner">
    <a href="index.php" class="navbar-logo">
      <span class="logo-ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
    </a>
    <div class="navbar-nav">
      <a href="index.php">Home</a>
      <a href="shop.php">Shop</a>
      <a href="workshops.php">Workshops</a>
      <a href="about.html">About</a>
    </div>
  </div>
</nav>

<main id="main-content" class="section-light">
  <div class="container checkout-container">
    <div class="section-top">
      <div class="section-top-left">
        <p class="eyebrow">Your Cart</p>
        <h2 style="text-align:left; color:var(--green);">Checkout</h2>
        <div class="gold-line"></div>
      </div>
      <button class="btn btn-brown" style="font-size:11px;" onclick="clearAllItemsBackend()">Clear All Items</button>
    </div>

    <?php if (empty($cartItems)): ?>
      <!-- EMPTY CART VIEW -->
      <div class="empty-cart-msg" id="empty-msg">
        <h2 class="logo-ar" style="color:var(--brown); font-size:32px;">السلة فارغة</h2>
        <p style="margin-bottom:20px; color:#666;">Your cart is empty. Browse our crafts or workshops to add items.</p>
        <div style="display:flex; gap:10px; justify-content:center;">
          <a href="shop.php" class="btn btn-green">Browse Shop</a>
          <a href="workshops.php" class="btn btn-gold">Browse Workshops</a>
        </div>
      </div>
    <?php else: ?>
      <!-- ACTIVE CART VIEW -->
      <div class="cart-active-content" id="cart-content">
        <table class="cart-table" id="cart-table">
          <thead>
            <tr><th>#</th><th>Item</th><th>Type</th><th>Price</th><th>Qty / Seats</th><th></th></tr>
          </thead>
          <tbody>
            <?php 
            $productsSubtotal = 0;
            $workshopsSubtotal = 0;
            
            foreach ($cartItems as $index => $item): 
              $itemPrice = (float)$item['price'];
              $itemQty = (int)$item['qty'];
              $rowTotal = $itemPrice * $itemQty;
              
              if ($item['type'] === 'workshop') {
                  $workshopsSubtotal += $rowTotal;
              } else {
                  $productsSubtotal += $rowTotal;
              }
            ?>
              <tr>
                <td><?php echo $index + 1; ?></td>
                <td>
                  <div style="display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" width="40" height="40" style="border-radius:4px; object-fit:cover;" alt="">
                    <div>
                      <div style="font-weight:600;"><?php echo htmlspecialchars($item['name']); ?></div>
                      <div style="font-size:12px; color:#777;">by <?php echo htmlspecialchars($item['artisan']); ?></div>
                    </div>
                  </div>
                </td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($item['type']); ?></td>
                <td>SAR <?php echo number_format($itemPrice, 2); ?></td>
                <td>
                  <div class="qty-range-wrap">
                    <!-- The Slider range max is safely configured directly by backend database metrics -->
                    <input type="range" 
                           min="1" 
                           max="<?php echo (int)$item['maxStock']; ?>" 
                           value="<?php echo $itemQty; ?>" 
                           oninput="updateCartQuantity(<?php echo $item['id']; ?>, this.value, this)" />
                    <span class="qty-val"><?php echo $itemQty; ?></span>
                  </div>
                </td>
                <td>
                  <button class="btn-delete" onclick="removeCartItem(<?php echo $item['id']; ?>)">&times;</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <?php 
        $serviceFee = 15;
        $grandTotal = $productsSubtotal + $workshopsSubtotal + $serviceFee;
        ?>
        <div class="summary-card">
          <p class="eyebrow" style="margin-bottom:22px; display:block;">Order Summary</p>
          <div class="summary-line"><span>Products</span><span class="sum-val" id="sum-products">SAR <?php echo number_format($productsSubtotal, 2); ?></span></div>
          <div class="summary-line"><span>Workshops</span><span class="sum-val" id="sum-workshops">SAR <?php echo number_format($workshopsSubtotal, 2); ?></span></div>
          <div class="summary-line" style="border-bottom: 1px solid #e0dcd4; padding-bottom:14px; margin-bottom:0;"><span>Service Fee</span><span>SAR <?php echo number_format($serviceFee, 2); ?></span></div>
          <div class="summary-total"><span>Total</span><span class="sum-val-grand" id="sum-grand">SAR <?php echo number_format($grandTotal, 2); ?></span></div>
          <a href="purchase-completed.html" class="btn btn-gold" style="display:block; width:100%; margin-top:24px; padding:16px; border-radius:6px; letter-spacing:1px; text-align:center; font-size:13px;">Complete Purchase</a>
          <div style="margin-top:16px; text-align:center;"><a href="shop.php" style="font-size:12px; color:#999; text-decoration:underline;">Continue Shopping</a></div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>

<script>
// Live update handler when slider shifts
function updateCartQuantity(id, newQty, sliderElement) {
    // Show new value instantly in text bubble next to slider track
    sliderElement.nextElementSibling.textContent = newQty;

    // Fire AJAX request to modify session values on the server instantly
    fetch('update-cart-item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, qty: parseInt(newQty) })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Refresh totals dynamically on interface
            document.getElementById('sum-products').textContent = 'SAR ' + data.productsSubtotal;
            document.getElementById('sum-workshops').textContent = 'SAR ' + data.workshopsSubtotal;
            document.getElementById('sum-grand').textContent = 'SAR ' + data.grandTotal;
        } else {
            alert(data.message || 'Error adjusting quantity.');
            location.reload(); // Fallback reset
        }
    });
}

function removeCartItem(id) {
    if (!confirm("Remove item from cart?")) return;

    // Halt any scrolling/sliding event emissions instantly
    if (window.event) {
        window.event.stopPropagation();
        window.event.preventDefault();
    }

    fetch('cart_remove.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Reload the layout entirely so the table rows wipe out completely
            window.location.reload(); 
        }
    })
    .catch(err => console.error("Error matching removal matrix:", err));
}

function clearAllItemsBackend() {
    if(!confirm("Clear everything?")) return;
    fetch('update-cart-item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'clear_all' })
    })
    .then(() => location.reload());
}
</script>
</body>
</html>