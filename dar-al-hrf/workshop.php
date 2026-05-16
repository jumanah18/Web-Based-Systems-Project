<?php
require 'db.php';

// 1. Get and sanitize the workshop ID from the URL (e.g., w1, w2, etc.)
$wid = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($wid)) {
    $workshopNotFound = true;
} else {
    // 2. Fetch the workshop details from the database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE pid = ? AND category = 'Workshop'");
    $stmt->execute([$wid]);
    $product = $stmt->fetch();

    if (!$product) {
        $workshopNotFound = true;
    } else {
        $workshopNotFound = false;
        
        // Check structural seat capacity status
        $seatsAvailable = isset($product['quantity']) ? (int)$product['quantity'] : 0;
        $isSoldOut = ($seatsAvailable <= 0);

        // 3. Parse dynamic attributes
        if (!empty($product['slots_json'])) {
            $slots = json_decode($product['slots_json'], true);
        } else {
            // Fallback default mock slots if database structural layouts do not contain schedules yet
            $slots = [
                ['date' => 'May 24, 2026', 'time' => '10:00 AM – 2:00 PM'],
                ['date' => 'May 31, 2026', 'time' => '2:00 PM – 6:00 PM']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>
    <?php echo !$workshopNotFound ? htmlspecialchars($product['name']) . " | Dar Al Hiraf" : "Workshop Not Found"; ?>
  </title>
  <link rel="stylesheet" href="style.css"/>
  <style>
    .workshop-detail-hero { height: 380px; position:relative; overflow:hidden; }
    .workshop-detail-hero img { width:100%; height:100%; object-fit:cover; }
    .workshop-detail-hero .hero-overlay { position:absolute;inset:0;background:linear-gradient(to right,rgba(36,60,33,.85) 0%,rgba(36,60,33,.4) 60%,transparent 100%); }
    .workshop-detail-hero .hero-content { position:absolute;top:0;left:0;bottom:0;display:flex;flex-direction:column;justify-content:center;padding:0 72px;max-width:620px; }
    .wd-wrap { padding: 56px 0 80px; background: var(--green); }
    .wd-grid { display:grid; grid-template-columns:1fr 380px; gap:48px; align-items:start; }
    .wd-card { background:rgba(255,255,255,.07); border:1px solid rgba(210,172,43,.3); border-radius:12px; padding:32px; position:sticky; top:90px; }
    .wd-card-title { font-size:11px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);margin-bottom:20px; }
    .wd-price-big { font-size:36px;font-weight:800;color:var(--gold);margin-bottom:8px; }
    .wd-price-sub { font-size:12px;color:rgba(255,255,255,.5);margin-bottom:24px; }
    .wd-detail-row { display:flex;align-items:flex-start;gap:10px;font-size:13px;color:rgba(255,255,255,.8);margin-bottom:12px;line-height:1.5; }
    .wd-detail-icon { font-size:16px;flex-shrink:0;margin-top:1px; }
    .wd-slots-title { font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin:24px 0 14px; }
    .slot-grid { display:flex;flex-direction:column;gap:10px;margin-bottom:22px; }
    .slot-lbl { cursor:pointer; }
    .slot-lbl input[type=radio] { position:absolute;opacity:0;width:0;height:0; }
    .slot-lbl input[type=radio]:checked + .slot-box { border-color:var(--gold);background:rgba(210,172,43,.18); }
    .slot-box { display:flex;flex-direction:column;padding:10px 16px;border:2px solid rgba(210,172,43,.3);border-radius:8px;background:rgba(255,255,255,.05);transition:all .2s; }
    .slot-box:hover { border-color:rgba(210,172,43,.7); }
    .slot-date { font-size:13px;font-weight:700;color:var(--white);margin-bottom:2px; }
    .slot-time { font-size:12px;color:rgba(255,255,255,.55); }
    .wd-desc { font-size:15px;color:rgba(255,255,255,.75);line-height:1.8;margin-bottom:28px; }
    .wd-info-boxes { display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:32px; }
    .wd-info-box { background:rgba(255,255,255,.07);border:1px solid rgba(210,172,43,.25);border-radius:8px;padding:18px; }
    .wd-info-box-label { font-size:10px;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);margin-bottom:8px; }
    .wd-info-box p { font-size:13px;color:rgba(255,255,255,.6);line-height:1.55; }
    #not-found { text-align:center;padding:80px 20px;background:var(--green); }
    .btn-disabled { opacity: 0.4; cursor: not-allowed !important; }
    @media(max-width:900px){ .wd-grid{grid-template-columns:1fr}.wd-card{position:static}.workshop-detail-hero .hero-content{padding:0 28px} .wd-info-boxes{grid-template-columns:1fr} }
  </style>
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
      <a href="shop.php">Shop</a>
      <a href="workshops.php" class="active">Workshops</a>
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

<main id="main-content">
  <div class="back-bar"><div class="container"><a href="workshops.php" class="back-link">&#8592; Back to Workshops</a></div></div>

  <?php if ($workshopNotFound): ?>
  <div id="not-found">
    <h2 style="color:var(--white);margin-bottom:12px;">Workshop Not Found</h2>
    <p style="color:rgba(255,255,255,.6);margin-bottom:24px;">This workshop doesn't exist or has ended.</p>
    <a href="workshops.php" class="btn btn-gold">View All Workshops</a>
  </div>

  <?php else: ?>
  <div class="workshop-detail-hero" id="wd-hero">
    <img id="wd-hero-img" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"/>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="hero-eyebrow" id="wd-tag"><?php echo htmlspecialchars(strtoupper($product['material'] ?? 'Crafts')); ?></p>
      <h1 class="hero-title-ar" lang="ar" id="wd-title-ar"><?php echo htmlspecialchars($product['name_ar'] ?? 'ورشة العمل'); ?></h1>
      <p class="hero-title-en" id="wd-title-en"><?php echo htmlspecialchars($product['name']); ?></p>
    </div>
  </div>

  <div class="wd-wrap" id="wd-body">
    <div class="container">
      <div class="wd-grid">

        <!-- Left Column -->
        <div>
          <p class="wc-tag" style="margin-bottom:8px;" id="wd-tag2"><?php echo htmlspecialchars(strtoupper($product['material'] ?? 'Crafts')); ?></p>
          <p class="wd-desc" id="wd-desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
          <div class="wd-info-boxes">
            <div class="wd-info-box"><p class="wd-info-box-label">Materials</p><p>All tools and materials are provided. Wear comfortable clothes suitable for hands-on craft work.</p></div>
            <div class="wd-info-box"><p class="wd-info-box-label">Certificate</p><p>Participants receive a completion certificate signed by the artisan and Dar Al Hiraf.</p></div>
            <div class="wd-info-box"><p class="wd-info-box-label">Policy</p><p>Free cancellation up to 48 hours before the session. Seat transfers are allowed.</p></div>
          </div>
        </div>

        <!-- Right Column: Interactive Session Card -->
        <div class="wd-card">
          <p class="wd-card-title">Session Details</p>
          <div class="wd-price-big" id="wd-price">SAR <?php echo number_format($product['price'], 2); ?></div>
          <p class="wd-price-sub">per person · all materials included</p>

          <div class="wd-detail-row"><span class="wd-detail-icon">📍</span><span id="wd-location"><?php echo htmlspecialchars($product['location'] ?? 'Dar Al Hiraf Studio'); ?></span></div>
          <div class="wd-detail-row"><span class="wd-detail-icon">⏱</span><span id="wd-duration"><?php echo htmlspecialchars($product['duration'] ?? 'Flexible hours'); ?></span></div>
          <div class="wd-detail-row">
            <span class="wd-detail-icon">👥</span>
            <span id="wd-seats">
              <?php echo $isSoldOut ? '<span style="color:#ff6b6b; font-weight:bold;">Sold Out</span>' : $seatsAvailable . ' seats available'; ?>
            </span>
          </div>

          <p class="wd-slots-title">Choose a Session</p>
          <fieldset style="border:none;padding:0;margin:0;" <?php echo $isSoldOut ? 'disabled' : ''; ?>>
            <legend class="sr-only">Available sessions</legend>
            <div class="slot-grid" id="wd-slots">
              <?php foreach ($slots as $index => $slot): ?>
                <label class="slot-lbl">
                  <input type="radio" name="slot" value="<?php echo $index; ?>" data-date="<?php echo htmlspecialchars($slot['date']); ?>" <?php echo ($index === 0 && !$isSoldOut) ? 'checked' : ''; ?>/>
                  <div class="slot-box">
                    <span class="slot-date"><?php echo htmlspecialchars($slot['date']); ?></span>
                    <span class="slot-time"><?php echo htmlspecialchars($slot['time']); ?></span>
                  </div>
                </label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <!-- Dynamic actions controlled via stock visibility -->
          <?php if($isSoldOut): ?>
            <button class="btn btn-gold btn-disabled" style="width:100%;margin-bottom:10px;font-size:13px;border:none;padding:14px;" disabled>Sold Out</button>
          <?php else: ?>
            <button class="btn btn-gold" style="width:100%;margin-bottom:10px;font-size:13px;border:none;cursor:pointer;padding:14px;" onclick="bookWorkshop()">Book Session</button>
            <button class="btn btn-outline-gold" style="width:100%;font-size:13px;cursor:pointer;padding:13px;" onclick="addWorkshopToCart()">+ Add to Cart</button>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
  <?php endif; ?>
</main>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div><p class="footer-brand-name" lang="ar">دار الحرف</p><p class="footer-desc">A platform connecting Saudi artisans with those who cherish authentic handmade crafts. Proudly part of The Year of Handicrafts 2026.</p></div>
      <div><p class="footer-col-title">Shop</p><div class="footer-links"><a href="shop.php">All Products</a><a href="shop.php">Pottery</a><a href="shop.php">Textiles</a><a href="shop.php">Metalwork</a><a href="shop.php">Fragrance</a></div></div>
      <div><p class="footer-col-title">More</p><div class="footer-links"><a href="about.html">About Us</a><a href="workshops.php">Workshops</a><a href="contact.php">Contact Us</a></div></div>
    </div>
  </div>
  <div class="footer-gold-bar"></div>
  <div class="container"><div class="footer-bottom"><p>&copy; 2026 Dar Al Hiraf — دار الحرف. All rights reserved.</p><p>A Ministry of Culture Initiative</p></div></div>
</footer>

<!-- Load cart core engine before executing page click bindings -->
<script src="cart.js"></script>

<script>
var currentWorkshop = <?php echo !$workshopNotFound ? json_encode([
  'id' => (int)$product['pid'],
  'titleEn' => $product['name'],
  'price' => (float)$product['price'],
  'img' => $product['image']
]) : 'null'; ?>;

function executeCartAddition() {
  if (!currentWorkshop) return false;
  
  var checked = document.querySelector('input[name=slot]:checked');
  if (!checked) { 
    alert('Please select an available session date first.'); 
    return false; 
  }
  
  var selectedDate = checked.getAttribute('data-date');
  
  // Guard configuration for handling cart operations safely
  if (typeof Cart !== 'undefined' && Cart.add) {
    Cart.add({
      id: currentWorkshop.id,
      name: currentWorkshop.titleEn + " (" + selectedDate + ")",
      price: currentWorkshop.price,
      image: currentWorkshop.img,
      type: 'workshop',
      qty: 1
    });
    return true;
  } else {
    alert('The cart platform encountered an error. Please reload the page.');
    return false;
  }
}

function bookWorkshop(){
  if(executeCartAddition()){
    window.location.href = 'checkout.html';
  }
}

function addWorkshopToCart(){
  if(executeCartAddition()){
    // Visual feedback indicator notice if optional helper method exists inside cart.js
    if (typeof updateCartBadge === 'function') {
        updateCartBadge();
    }
  }
}
</script>

<script>
  (function(){
    var link = document.getElementById('adminNavLink');
    if(!link) return;
    if(sessionStorage.getItem('adminLoggedIn')){
      link.textContent = '→ Admin Dashboard';
      link.href = 'admin-dashboard.php';
      link.className = 'admin-logout-nav-btn';
    }
  })();
</script>
</body>
</html>