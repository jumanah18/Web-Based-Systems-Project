<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Shop</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- NAVBAR -->
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
    </div>  </div>
</nav>

</div>
<main id="main-content">

<!-- SHOP HEADER -->

<div class="back-bar"><div class="container"><a href="index.php" class="back-link">&larr; Back to Home</a></div></div>
<div class="cat-strip"><div class="cat-strip-inner"><a href="shop.php" class="cat-item active">All Crafts</a><a href="shop.php" class="cat-item">Pottery</a><a href="shop.php" class="cat-item">Textiles</a><a href="shop.php" class="cat-item">Metalwork</a><a href="shop.php" class="cat-item">Leather</a><a href="shop.php" class="cat-item">Fragrance</a></div></div>
<div class="shop-header">
  <h1 lang="ar">المجموعة الكاملة</h1>
  <?php $count = $pdo->query("SELECT COUNT(*) FROM products WHERE category != 'Workshop'")->fetchColumn(); ?>
  <p><?php echo $count; ?> handcrafted products by Saudi artisans across the Kingdom</p>
</div>

<!-- PRODUCTS -->
<div class="shop-content">
  <div class="container">
    <div class="product-grid">
      <?php
      require_once 'db.php';
      $products = $pdo->query("SELECT * FROM products WHERE category != 'Workshop' ORDER BY pid ASC")->fetchAll();
      foreach ($products as $p):
      ?>
      <a href="product.php?id=<?php echo $p['pid']; ?>" class="product-card">
        <div class="image-container">
          <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
        </div>
        <div class="product-info">
          <p class="category"><?php echo strtoupper(htmlspecialchars($p['category'])); ?></p>
          <h3 class="product-title"><?php echo htmlspecialchars($p['name']); ?></h3>
          <p class="author">By: <?php echo htmlspecialchars($p['artisan']); ?></p>
          <div class="price-row">
            <span class="currency">SAR</span>
            <span class="price"><?php echo number_format($p['price'], 0); ?></span>
          </div>
          <button class="view-btn" onclick="event.stopPropagation();Cart.add({id:<?php echo $p['pid']; ?>,name:<?php echo json_encode($p['name']); ?>,price:<?php echo (float)$p['price']; ?>,image:<?php echo json_encode($p['image']); ?>,artisan:<?php echo json_encode($p['artisan']); ?>,type:'product',qty:1})">+ Add to Cart</button>
        </div>
      </a>
      <?php endforeach; ?>
    </div></div>
  </div>
</div>

<!-- FOOTER -->
</main>
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <p class="footer-brand-name" lang="ar">دار الحرف</p>
        <p class="footer-desc">A platform connecting Saudi artisans with those who cherish authentic handmade crafts rooted in heritage. Proudly part of The Year of Handicrafts 2026 initiative by the Ministry of Culture.</p>
      </div>
      <div>
        <p class="footer-col-title">Shop</p>
        <div class="footer-links" role="list">
          <a href="shop.php">All Products</a>
          <a href="shop.php">Pottery</a>
          <a href="shop.php">Textiles</a>
          <a href="shop.php">Metalwork</a>
          <a href="shop.php">Leather</a>
        </div>
      </div>
      <div>
        <p class="footer-col-title">More</p>
        <div class="footer-links" role="list">
          <a href="about.html">About Us</a>
          <a href="workshops.php">Workshops</a>
          <a href="contact.html">Contact Us</a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-gold-bar"></div>
  <div class="container">
    <div class="footer-bottom">
      <p>© 2026 Dar Al Hiraf — دار الحرف. All rights reserved.</p>
      <p>A Ministry of Culture Initiative</p>
    </div>
  </div>
</footer>
<script>
  (function(){
    var link = document.getElementById('adminNavLink');
    if(!link) return;
    if(sessionStorage.getItem('adminLoggedIn')){
      link.textContent = '→ Admin Dashboard';
      link.href = 'admin-dashboard.php';
    }
  })();
</script>
<script src="cart.js"></script>
</body>
</html>