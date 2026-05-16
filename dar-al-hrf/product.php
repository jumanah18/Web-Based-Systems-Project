<?php
require 'db.php';

$pid     = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt    = $pdo->prepare("SELECT * FROM products WHERE pid = ? AND category != 'Workshop'");
$stmt->execute([$pid]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: shop.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo htmlspecialchars($product['name']); ?> | Dar Al Hiraf</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<a href="#main-content" class="skip-link">Skip to main content</a>

<nav class="navbar" aria-label="Main navigation">
  <div class="navbar-inner">
    <a href="index.php" class="navbar-logo">
      <span class="logo-ar" lang="ar">دار الحرف</span>
      <span class="logo-en">Dar Al Hiraf</span>
    </a>
    <div class="navbar-nav">
      <a href="index.php">Home</a>
      <a href="shop.php" class="active">Shop</a>
      <a href="workshops.php">Workshops</a>
      <a href="about.html">About</a>
    </div>
    <form class="navbar-search" onsubmit="event.preventDefault();var v=this.querySelector('input').value.trim();if(v)window.location.href='search.html?q='+encodeURIComponent(v);" role="search">
      <input class="navbar-search-input" type="search" placeholder="Search..." aria-label="Search">
      <button class="navbar-search-btn" type="submit" aria-label="Search">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </button>
    </form>
    <div class="navbar-right">
      <a id="adminNavLink" href="admin-login.php" class="navbar-admin-link">Admin</a>
      <a href="checkout.html" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" stroke="#1a1a1a" fill="none" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge"></span>
      </a>
    </div>
  </div>
</nav>

<!-- Breadcrumb -->
<div class="breadcrumb">
  <div class="breadcrumb-row container">
    <a href="index.php">Home</a>
    <span class="bc-sep">›</span>
    <a href="shop.php">Shop</a>
    <span class="bc-sep">›</span>
    <span class="bc-current"><?php echo htmlspecialchars($product['name']); ?></span>
  </div>
</div>

<main id="main-content">
  <div class="detail-wrap">
    <div class="container">
      <div class="detail-grid">

        <!-- Image -->
        <div>
          <div class="detail-main-img">
            <img src="<?php echo htmlspecialchars($product['image']); ?>"
                 alt="<?php echo htmlspecialchars($product['name']); ?>" />
          </div>
        </div>

        <!-- Info -->
        <div>
          <p class="d-category"><?php echo htmlspecialchars($product['category']); ?></p>
          <h1 class="d-name-en"><?php echo htmlspecialchars($product['name']); ?></h1>
          <p class="d-artisan">Crafted by <strong><?php echo htmlspecialchars($product['artisan']); ?></strong></p>

          <div class="d-price-block">
            <span class="d-price"><small>SAR </small><?php echo number_format($product['price'], 2); ?></span>
          </div>

          <p class="d-desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

          <div class="d-meta">
            <?php if (!empty($product['material'])): ?>
            <div class="d-meta-row">
              <span class="d-meta-key">Material</span>
              <span class="d-meta-val"><?php echo htmlspecialchars($product['material']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($product['quantity'])): ?>
            <div class="d-meta-row">
              <span class="d-meta-key">In Stock</span>
              <span class="d-meta-val"><?php echo (int)$product['quantity']; ?> available
              </span>
            </div>
            <?php endif; ?>
          </div>

          <!-- Quantity -->
          <div class="d-meta-row" style="margin-bottom:18px;align-items:center;gap:14px;">
            <span class="d-meta-key">Quantity</span>
            <input type="number" id="qtyInput" value="1" min="1"
                   max="<?php echo (int)($product['quantity'] ?? 99); ?>"
                   style="width:70px;padding:8px;border:1.5px solid #ddd;border-radius:6px;font-size:14px;text-align:center;" />
          </div>

          <div class="d-btns">
            <button class="btn btn-green" onclick="handleAddToCart()" style="font-size:14px;padding:14px 24px;">
              &#x1F6D2; Add to Cart
            </button>
            <a href="checkout.html" class="btn btn-gold" style="font-size:14px;padding:14px 24px;text-align:center;">
              Checkout &rarr;
            </a>
          </div>

          <div class="trust-bar">
            <span>&#10003; Handmade by Saudi artisans</span>
            <span>&#10003; Authentic heritage craft</span>
            <span>&#10003; Secure checkout</span>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Help popup trigger (Task 14) -->
  <div style="position:fixed;bottom:24px;left:24px;z-index:999;">
    <button onclick="document.getElementById('helpModal').style.display='flex'"
            style="background:var(--green);color:#fff;border:none;border-radius:50%;width:44px;height:44px;font-size:20px;cursor:pointer;box-shadow:0 4px 14px rgba(0,0,0,0.2);"
            aria-label="Help">?</button>
  </div>
  <div id="helpModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:36px;max-width:420px;width:90%;position:relative;">
      <button onclick="document.getElementById('helpModal').style.display='none'"
              style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:22px;cursor:pointer;">&times;</button>
      <h3 style="color:var(--green);margin-bottom:12px;">&#x2753; Product Help</h3>
      <p style="font-size:14px;color:#555;line-height:1.7;">
        Select your desired quantity and click <strong>Add to Cart</strong> to add this item.<br><br>
        Click <strong>Checkout</strong> to review your cart and complete your purchase.<br><br>
        All products are handmade by Saudi artisans and shipped across the Kingdom.
      </p>
    </div>
  </div>
</main>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <p class="footer-brand-name" lang="ar">دار الحرف</p>
        <p class="footer-desc">A platform connecting Saudi artisans with those who cherish authentic handmade crafts rooted in heritage.</p>
      </div>
      <div>
        <p class="footer-col-title">Shop</p>
        <div class="footer-links">
          <a href="shop.php">All Products</a>
          <a href="workshops.php">Workshops</a>
        </div>
      </div>
      <div>
        <p class="footer-col-title">More</p>
        <div class="footer-links">
          <a href="about.html">About Us</a>
          <a href="contact.html">Contact Us</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2026 Dar Al Hiraf. All rights reserved.</p>
    </div>
  </div>
</footer>

<script>
  function handleAddToCart() {
    var qty = parseInt(document.getElementById('qtyInput').value) || 1;
    Cart.add({
      id:      <?php echo $product['pid']; ?>,
      name:    <?php echo json_encode($product['name']); ?>,
      price:   <?php echo (float)$product['price']; ?>,
      image:   <?php echo json_encode($product['image']); ?>,
      artisan: <?php echo json_encode($product['artisan']); ?>,
      type:    'product',
      qty:     qty
    });
  }
</script>
<script src="cart.js"></script>
</body>
</html>