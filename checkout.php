<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

// Fallback if cart session isn't initialized
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cartUpdated = false;

if (!empty($cartItems)) {
    foreach ($cartItems as $indexKey => $item) {
        // Look for 'pid' (workshops) OR fallback to 'id' (standard products)
        $productId = 0;
        if (isset($item['pid'])) {
            $productId = (int)$item['pid'];
        } elseif (isset($item['id'])) {
            $productId = (int)$item['id'];
        }
        
        if ($productId <= 0) {
            continue;
        }

        // Standardize the session entry: Ensure 'pid' is always populated
        if (!isset($_SESSION['cart'][$indexKey]['pid'])) {
            $_SESSION['cart'][$indexKey]['pid'] = $productId;
            $cartItems[$indexKey]['pid'] = $productId;
        }

        // Query the source of truth database for up-to-the-minute stock/seats
        $stmt = $pdo->prepare("SELECT quantity, artisan FROM products WHERE pid = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        
        if ($product) {
            $currentStock = (int)$product['quantity'];
            
            // If stock/seats dropped, clamp it to the maximum available limit
            if ($item['qty'] > $currentStock) {
                $_SESSION['cart'][$indexKey]['qty'] = $currentStock;
                $cartItems[$indexKey]['qty'] = $currentStock;
                $cartUpdated = true;
            }
            
            // Re-verify and bind live inventory levels to the item tracking space
            $_SESSION['cart'][$indexKey]['maxStock'] = $currentStock;
            $cartItems[$indexKey]['maxStock'] = $currentStock;
            
            // Make sure artisan data matches current database value
            if (!empty($product['artisan'])) {
                $_SESSION['cart'][$indexKey]['artisan'] = $product['artisan'];
                $cartItems[$indexKey]['artisan'] = $product['artisan'];
            }
        } else {
            // Drop completely if product/workshop no longer exists in DB
            unset($_SESSION['cart'][$indexKey]);
            unset($cartItems[$indexKey]);
            $cartUpdated = true;
        }
    }
    
    if ($cartUpdated) {
        $cartItems = $_SESSION['cart'];
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
      <div class="empty-cart-msg" id="empty-msg">
        <h2 class="logo-ar" style="color:var(--brown); font-size:32px;">السلة فارغة</h2>
        <p style="margin-bottom:20px; color:#666;">Your cart is empty. Browse our crafts or workshops to add items.</p>
        <div style="display:flex; gap:10px; justify-content:center;">
          <a href="shop.php" class="btn btn-green">Browse Shop</a>
          <a href="workshops.php" class="btn btn-gold">Browse Workshops</a>
        </div>
      </div>
    <?php else: ?>
      <div class="cart-active-content" id="cart-content">
        <table class="cart-table" id="cart-table">
          <thead>
            <tr><th>#</th><th>Item</th><th>Type</th><th>Price</th><th>Qty / Seats</th><th></th></tr>
          </thead>
          <tbody>
            <?php 
            $productsSubtotal = 0;
            $workshopsSubtotal = 0;
            $counter = 1;
            
            foreach ($cartItems as $indexKey => $item): 
              $itemPrice = isset($item['price']) ? (float)$item['price'] : 0.0;
              $itemQty = isset($item['qty']) ? (int)$item['qty'] : 1;
              $maxStock = isset($item['maxStock']) ? (int)$item['maxStock'] : 10;
              
              if ($maxStock < 1) { $maxStock = 1; }
              if ($itemQty > $maxStock) { $itemQty = $maxStock; }
              
              $rowTotal = $itemPrice * $itemQty;
              
              if (isset($item['type']) && $item['type'] === 'workshop') {
                  $workshopsSubtotal += $rowTotal;
              } else {
                  $productsSubtotal += $rowTotal;
              }
              
              $itemImage = !empty($item['image']) ? $item['image'] : 'images/default.jpg';
              $artisanName = !empty($item['artisan']) ? $item['artisan'] : 'Dar Al Hiraf Artisan';
              
              // Force the cart key to safely be treated as a clean string literal instance
              $safeCartKey =strval($indexKey);
            ?>
              <tr>
                <td><?php echo $counter++; ?></td>
                <td>
                  <div style="display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo htmlspecialchars($itemImage); ?>" width="40" height="40" style="border-radius:4px; object-fit:cover;" alt="">
                    <div>
                      <div style="font-weight:600; text-align: left;"><?php echo htmlspecialchars($item['name']); ?></div>
                      <div style="font-size:12px; color:#777; text-align: left;">by <?php echo htmlspecialchars($artisanName); ?></div>
                    </div>
                  </div>
                </td>
                <td style="text-transform: capitalize;"><?php echo htmlspecialchars($item['type'] ?? 'product'); ?></td>
                <td>SAR <?php echo number_format($itemPrice, 2); ?></td>
                <td>
                  <div class="qty-range-wrap">
                    <input type="range" 
                           min="1" 
                           max="<?php echo $maxStock; ?>" 
                           value="<?php echo $itemQty; ?>" 
                           oninput="updateCartQuantity('<?php echo htmlspecialchars($safeCartKey); ?>', this.value, this)" />
                    <span class="qty-val"><?php echo $itemQty; ?></span>
                  </div>
                </td>
                <td>
                  <button class="btn-delete" onclick="removeCartItem('<?php echo htmlspecialchars($safeCartKey); ?>')">&times;</button>
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
          <button onclick="completePurchase()" class="btn btn-gold" style="display:block; width:100%; margin-top:24px; padding:16px; border-radius:6px; letter-spacing:1px; text-align:center; font-size:13px;">Complete Purchase</button>
          <div style="margin-top:16px; text-align:center;"><a href="shop.php" style="font-size:12px; color:#999; text-decoration:underline;">Continue Shopping</a></div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</main>
<script src="cart.js"></script>
<script>
function updateCartQuantity(cartKey, newQty, sliderElement) {
    sliderElement.nextElementSibling.textContent = newQty;

    fetch('update-cart-item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart_key: cartKey, qty: parseInt(newQty) })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('sum-products').textContent = 'SAR ' + data.productsSubtotal;
            document.getElementById('sum-workshops').textContent = 'SAR ' + data.workshopsSubtotal;
            document.getElementById('sum-grand').textContent = 'SAR ' + data.grandTotal;
        } else {
            alert(data.message || 'Error adjusting quantity.');
            location.reload();
        }
    });
}

function removeCartItem(cartKey) {
    if (!confirm("Remove item from cart?")) return;

    fetch('cart_remove.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart_key: String(cartKey) })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload(); 
        } else {
            alert(data.message || 'Could not delete item.');
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
  async function completePurchase() {
    var cart = await Cart.get();

    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    await fetch('save_purchase.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart: cart })
    });

    await Cart.clear();
    window.location.href = 'purchase-completed.php';
}
</script>
</body>
</html>