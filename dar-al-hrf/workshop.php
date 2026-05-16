<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

// 1. Get workshop ID from URL
$wid = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($wid)) {
    $workshopNotFound = true;
} else {
    // 2. Fetch details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE pid = ? AND category = 'Workshop'");
    $stmt->execute([$wid]);
    $product = $stmt->fetch();

    if (!$product) {
        $workshopNotFound = true;
    } else {
        $workshopNotFound = false;
        $seatsAvailable = isset($product['quantity']) ? (int)$product['quantity'] : 0;
        $isSoldOut = ($seatsAvailable <= 0);

        if (!empty($product['slots_json'])) {
            $slots = json_decode($product['slots_json'], true);
        } else {
            $slots = [
                ['date' => 'May 24, 2026', 'time' => '10:00 AM – 2:00 PM'],
                ['date' => 'May 31, 2026', 'time' => '2:00 PM – 6:00 PM']
            ];
        }

        // 3. SIMPLE PHP ADD TO CART LOGIC (No Javascript required)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_workshop'])) {
            $slotIndex = (int)$_POST['slot'];
            $selectedDate = $slots[$slotIndex]['date'];
            $qty = (int)$_POST['quantity'];
            
            // Build a clean item entry name
            $fullName = $product['name'] . " (" . $selectedDate . ")";
            $cartKey = $product['pid'] . "_" . md5($fullName);

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Save to cart session
            if (isset($_SESSION['cart'][$cartKey])) {
                $_SESSION['cart'][$cartKey]['qty'] += $qty;
            } else {
                $_SESSION['cart'][$cartKey] = [
                    'pid'     => $product['pid'],
                    'name'    => $fullName,
                    'price'   => (float)$product['price'],
                    'image'   => $product['image'],
                    'artisan' => $product['artisan'] ?? 'Dar Al Hiraf Artisan',
                    'type'    => 'workshop',
                    'qty'     => $qty
                ];
            }

            // If they clicked "Book Session", send them straight to checkout
            if ($_POST['action_type'] === 'checkout') {
                header('Location: checkout.php');
                exit;
            } else {
                // Otherwise, show a clean confirmation message
                $successMessage = "Added to cart successfully!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo !$workshopNotFound ? htmlspecialchars($product['name']) . " | Dar Al Hiraf" : "Workshop Not Found"; ?></title>
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
    .slot-lbl { cursor:pointer; position: relative; display: block; }
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
      <a href="checkout.php" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
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
    <a href="workshops.php" class="btn btn-gold">View All Workshops</a>
  </div>

  <?php else: ?>
  
  <?php if(isset($successMessage)): ?>
    <div style="background: #27ae60; color: white; text-align: center; padding: 15px; font-weight: bold;">
      <?php echo $successMessage; ?> ➔ <a href="checkout.php" style="color: var(--gold); text-decoration: underline;">Go to Cart</a>
    </div>
  <?php endif; ?>

  <div class="workshop-detail-hero">
    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt=""/>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <p class="hero-eyebrow"><?php echo htmlspecialchars(strtoupper($product['material'] ?? 'Crafts')); ?></p>
      <h1 class="hero-title-ar" lang="ar"><?php echo htmlspecialchars($product['name_ar'] ?? 'ورشة العمل'); ?></h1>
      <p class="hero-title-en"><?php echo htmlspecialchars($product['name']); ?></p>
    </div>
  </div>

  <div class="wd-wrap">
    <div class="container">
      <div class="wd-grid">

        <!-- Left Column -->
        <div>
          <p class="wd-desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        </div>

        <!-- Right Column: Interactive Session Card Form -->
        <form method="POST" action="" class="wd-card">
          <input type="hidden" name="add_workshop" value="1">
          <!-- Hidden input controlled by which button is clicked -->
          <input type="hidden" id="action-type" name="action_type" value="cart">

          <p class="wd-card-title">Session Details</p>
          <div class="wd-price-big">SAR <?php echo number_format($product['price'], 2); ?></div>
          <p class="wd-price-sub">per person · all materials included</p>

          <div class="wd-detail-row"><span>📍</span><span><?php echo htmlspecialchars($product['location'] ?? 'Dar Al Hiraf Studio'); ?></span></div>
          <div class="wd-detail-row"><span>⏱</span><span><?php echo htmlspecialchars($product['duration'] ?? 'Flexible hours'); ?></span></div>
          <div class="wd-detail-row"><span>👥</span><span><?php echo $isSoldOut ? 'Sold Out' : $seatsAvailable . ' seats available'; ?></span></div>

          <p class="wd-slots-title">Choose a Session</p>
          <div class="slot-grid">
            <?php foreach ($slots as $index => $slot): ?>
              <label class="slot-lbl">
                <input type="radio" name="slot" value="<?php echo $index; ?>" <?php echo $index === 0 ? 'checked' : ''; ?> <?php echo $isSoldOut ? 'disabled' : ''; ?>/>
                <div class="slot-box">
                  <span class="slot-date"><?php echo htmlspecialchars($slot['date']); ?></span>
                  <span class="slot-time"><?php echo htmlspecialchars($slot['time']); ?></span>
                </div>
              </label>
            <?php endforeach; ?>
          </div>

          <!-- Quantity Dropdown UI Selection -->
          <?php if(!$isSoldOut): ?>
            <div style="margin-bottom: 24px;">
              <p class="wd-slots-title" style="margin: 0 0 10px 0;">Select Tickets</p>
              <select name="quantity" style="width:100%; padding:12px 16px; background:rgba(255,255,255,.05); border:2px solid rgba(210,172,43,.3); border-radius:8px; color:var(--white); font-size:13px; font-weight: 600; outline:none; cursor:pointer;">
                <?php 
                  $maxTickets = min($seatsAvailable, 10);
                  for($i = 1; $i <= $maxTickets; $i++): 
                ?>
                  <option value="<?php echo $i; ?>" style="background: rgb(26, 43, 24);"><?php echo $i; ?> Ticket<?php echo $i > 1 ? 's' : ''; ?></option>
                <?php endfor; ?>
              </select>
            </div>
          <?php endif; ?>

          <!-- Action Buttons -->
          <?php if($isSoldOut): ?>
            <button type="button" class="btn btn-gold btn-disabled" style="width:100%; border:none; padding:14px;" disabled>Sold Out</button>
          <?php else: ?>
            <button type="submit" class="btn btn-gold" style="width:100%; margin-bottom:10px; border:none; padding:14px; cursor:pointer;" onclick="document.getElementById('action-type').value='checkout';">Book Session</button>
            <button type="submit" class="btn btn-outline-gold" style="width:100%; border:none; padding:13px; cursor:pointer;" onclick="document.getElementById('action-type').value='cart';">+ Add to Cart</button>
          <?php endif; ?>
        </form>

      </div>
    </div>
  </div>
  <?php endif; ?>
</main>
</body>
</html>