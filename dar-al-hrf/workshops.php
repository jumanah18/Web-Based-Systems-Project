<?php
require 'db.php';

// Fetch all available workshops from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'Workshop' ORDER BY pid DESC");
    $stmt->execute();
    $workshops = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Fallback to empty array if query fails
    $workshops = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Workshops</title>
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

  <!-- BACK ARROW -->
  <div class="back-bar">
    <div class="container">
      <a href="index.php" class="back-link">&#8592; Back to Home</a>
    </div>
  </div>

  <div class="shop-header">
    <h1 lang="ar">ورش العمل</h1>
    <p>Hands-on Saudi craft workshops led by master artisans across the Kingdom</p>
  </div>

  <!-- WORKSHOPS WITH BOOKING -->
  <section class="section section-green">
    <div class="container">
      <div class="workshops-full-grid">

        <?php if (empty($workshops)): ?>
          <div style="text-align: center; width: 100%; padding: 40px 0; color: #fff;">
            <p>No workshops are currently scheduled. Please check back soon!</p>
          </div>
        <?php else: ?>
          <?php foreach ($workshops as $workshop): ?>
            <!-- DYNAMIC WORKSHOP CARD -->
            <div class="workshop-full-card" id="workshop-<?php echo $workshop['pid']; ?>">
              <div class="wf-img">
                <img src="<?php echo htmlspecialchars($workshop['image'] ?: 'images/placeholder-workshop.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($workshop['name']); ?>" />
              </div>
              <div class="wf-body">
                <div class="wf-info">
                  <!-- Tag fallback to Material field -->
                  <p class="wc-tag"><?php echo htmlspecialchars($workshop['material'] ?: 'Heritage Craft'); ?></p>
                  <p class="wc-title-en"><?php echo htmlspecialchars($workshop['name']); ?></p>
                  <p class="wc-artisan" style="font-size: 14px; margin: -5px 0 10px 0; color: #666;">
                    Led by: <strong><?php echo htmlspecialchars($workshop['artisan'] ?: 'Master Artisan'); ?></strong>
                  </p>
                  <p class="wc-desc"><?php echo nl2br(htmlspecialchars($workshop['description'])); ?></p>

                  <div class="wf-details">
                    <div class="wf-detail-row">
                      <span class="wf-detail-icon">&#128205;</span>
                      <span><strong>Location:</strong> <?php echo htmlspecialchars($workshop['location'] ?: 'To Be Announced'); ?></span>
                    </div>
                    <div class="wf-detail-row">
                      <span class="wf-detail-icon">&#8987;</span>
                      <span><strong>Duration:</strong> <?php echo htmlspecialchars($workshop['duration'] ?: '3 hours'); ?> &nbsp;&nbsp; <strong>Seats:</strong> <?php echo (int)($workshop['quantity'] ?? 0); ?> available</span>
                    </div>
                    <div class="wf-detail-row">
                      <span class="wf-detail-icon">&#128203;</span>
                      <span><strong>Price per person:</strong> <span class="wf-price"><?php echo number_format($workshop['price'], 2); ?> SAR</span></span>
                    </div>
                  </div>

                  <div class="wf-booking">
                    <p class="wf-booking-title">Select a Date &amp; Time</p>
                    <fieldset>
                      <legend class="sr-only">Available sessions for <?php echo htmlspecialchars($workshop['name']); ?></legend>
                      <div class="time-slots">
                        <?php 
                        // Parse available sessions. Expecting data separated by dual bars '||' and clean strings
                        $sessionField = $workshop['sessions'] ?? '';
                        $sessionsList = !empty($sessionField) ? explode('||', $sessionField) : [];
                        
                        if (empty($sessionsList)) {
                            // Safe default fallback if data empty
                            $sessionsList = ["Upcoming Schedule | Contact Coordinator"];
                        }

                        foreach ($sessionsList as $index => $sessionText): 
                            $sessionText = trim($sessionText);
                            $parts = explode('|', $sessionText);
                            $datePart = trim($parts[0] ?? 'Date TBD');
                            $timePart = trim($parts[1] ?? 'Time TBD');
                            $radioValue = 'pid-' . $workshop['pid'] . '-opt-' . $index;
                        ?>
                          <label class="time-slot">
                            <input type="radio" name="session-w<?php echo $workshop['pid']; ?>" value="<?php echo htmlspecialchars($sessionText); ?>" <?php echo $index === 0 ? 'checked' : ''; ?> />
                            <span class="slot-box">
                              <span class="slot-date"><?php echo htmlspecialchars($datePart); ?></span>
                              <span class="slot-time"><?php echo htmlspecialchars($timePart); ?></span>
                            </span>
                          </label>
                        <?php endforeach; ?>
                      </div>
                    </fieldset>
                    
                    <div class="booking-actions">
                      <button class="btn btn-outline-gold" onclick="handleWorkshopAddToCart(<?php echo $workshop['pid']; ?>, <?php echo json_encode($workshop['name']); ?>, <?php echo (float)$workshop['price']; ?>, <?php echo json_encode($workshop['image']); ?>, <?php echo json_encode($workshop['artisan']); ?>)">
                        + Add to Cart
                      </button>
                      <button class="help-btn" onclick="openWorkshopHelp()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg> Need Help?
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div><!-- /workshops-full-grid -->
    </div>
  </section>

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
          <a href="shop.php" role="listitem">All Products</a>
          <a href="shop.php" role="listitem">Pottery</a>
          <a href="shop.php" role="listitem">Textiles</a>
          <a href="shop.php" role="listitem">Metalwork</a>
          <a href="shop.php" role="listitem">Leather</a>
          <a href="shop.php" role="listitem">Fragrance</a>
        </div>
      </div>
      <div>
        <p class="footer-col-title">More</p>
        <div class="footer-links" role="list">
          <a href="about.html" role="listitem">About Us</a>
          <a href="workshops.php" role="listitem">Workshops</a>
          <a href="contact.php">Contact Us</a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-gold-bar"></div>
  <div class="container">
    <div class="footer-bottom">
      <p>&copy; 2026 Dar Al Hiraf &mdash; دار الحرف. All rights reserved.</p>
      <p>A Ministry of Culture Initiative</p>
    </div>
  </div>
</footer>

<!-- WORKSHOP NEED HELP MODAL -->
<div class="help-overlay" id="workshopHelpOverlay" onclick="if(event.target===this)closeWorkshopHelp()">
  <div class="help-modal" role="dialog" aria-modal="true" aria-labelledby="workshopHelpTitle">
    <button class="help-close" onclick="closeWorkshopHelp()" aria-label="Close">&times;</button>
    <div class="help-modal-header">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      <h3 id="workshopHelpTitle">Need Help?</h3>
    </div>
    <div class="help-item">
      <h4>What to Bring</h4>
      <p>No experience needed — all materials and tools are provided by the artisan. Wear comfortable clothes you don't mind getting a little messy.</p>
    </div>
    <div class="help-item">
      <h4>Cancellation Policy</h4>
      <p>Free cancellation up to 48 hours before the session. Cancellations within 48 hours receive a 50% refund. No-shows are non-refundable.</p>
    </div>
    <div class="help-item">
      <h4>Accessibility</h4>
      <p>Please contact us before booking if you have accessibility requirements. We will do our best to accommodate all participants.</p>
    </div>
    <div class="help-item">
      <h4>Have More Questions?</h4>
      <p>Visit our <a href="about.html" style="color:var(--gold)">About page</a> or reach out via the Contact Us link in the footer. We're happy to help.</p>
    </div>
    <div class="help-modal-footer">
      <button onclick="closeWorkshopHelp()">Got it, thanks!</button>
    </div>
  </div>
</div>

<script>
  function openWorkshopHelp() { document.getElementById("workshopHelpOverlay").classList.add("open"); document.body.style.overflow="hidden"; }
  function closeWorkshopHelp() { document.getElementById("workshopHelpOverlay").classList.remove("open"); document.body.style.overflow=""; }
  document.addEventListener("keydown", e => { if(e.key==="Escape") closeWorkshopHelp(); });

  // Dedicated function to handle adding selected workshop details to cart
  function handleWorkshopAddToCart(pid, baseName, price, image, artisan) {
    var selectedRadio = document.querySelector('input[name="session-w' + pid + '"]:checked');
    var sessionDetails = selectedRadio ? selectedRadio.value : "General Admission";
    
    // Combine structural item details with custom selection configurations
    var finalItemName = baseName + " (" + sessionDetails.replace('|', '-') + ")";

    if(typeof Cart !== 'undefined' && Cart.add) {
        Cart.add({
          id: pid,
          name: finalItemName,
          price: parseFloat(price),
          image: image,
          artisan: artisan,
          type: 'workshop',
          qty: 1
        });
    } else if (typeof addToCart === 'function') {
        // Fallback execution option matching your legacy inline script footprints
        addToCart(pid, finalItemName, price, 1);
    } else {
        console.warn("Cart management subsystem instantiation error.");
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
    }
  })();
</script>
<script src="cart.js"></script>
</body>
</html>