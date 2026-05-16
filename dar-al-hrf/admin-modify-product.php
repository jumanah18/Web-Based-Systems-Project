<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}
require 'db.php';

$success = '';
$error   = '';

// ── DELETE ────────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $pid = intval($_POST['pid']);
    $stmt = $pdo->prepare("DELETE FROM products WHERE pid = ? AND category != 'Workshop'");
    $stmt->execute([$pid]);
    $success = 'Product deleted successfully.';
}

// ── UPDATE ────────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $pid      = intval($_POST['pid']);
    $name     = trim($_POST['editName']     ?? '');
    $desc     = trim($_POST['editDesc']     ?? '');
    $price    = trim($_POST['editPrice']    ?? '');
    $category = trim($_POST['editCategory'] ?? '');
    $artisan  = trim($_POST['editArtisan']  ?? '');
    $material = trim($_POST['editRegion']   ?? '');
    $quantity = intval($_POST['editQty']    ?? 0);
    $currentImg = trim($_POST['currentImg'] ?? '');

    if (!$name || !$price || !$category || !$artisan) {
        $error = 'Please fill in all required fields.';
    } else {
        $newImg = $currentImg;

        // Handle new image if uploaded
        if (isset($_FILES['editProductImage']) && $_FILES['editProductImage']['error'] === UPLOAD_ERR_OK) {
            $file    = $_FILES['editProductImage'];
            $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Image must be JPG, PNG, or WEBP.';
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $error = 'Image must be under 5MB.';
            } else {
                $filename  = 'product_' . time() . '_' . preg_replace('/[^a-z0-9]/', '', strtolower($name)) . '.' . $ext;
                $uploadDir = __DIR__ . '/images/';
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $newImg = 'images/' . $filename;
                } else {
                    $error = 'Failed to upload image.';
                }
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("
                UPDATE products
                SET name=?, category=?, description=?, material=?, price=?, artisan=?, image=?, quantity=?
                WHERE pid=? AND category != 'Workshop'
            ");
            $stmt->execute([$name, $category, $desc, $material, $price, $artisan, $newImg, $quantity, $pid]);
            $success = 'Product updated successfully.';
        }
    }
}

// ── FETCH ALL PRODUCTS (not workshops) ───────────────────────────────────────
$products = $pdo->query("SELECT * FROM products WHERE category != 'Workshop' ORDER BY pid ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Modify Product</title>
  <link rel="stylesheet" href="style.css" />
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
      <a href="workshops.php">Workshops</a>
      <a href="about.html">About</a>
    </div>
    <div class="navbar-right">
      <a id="adminNavLink" href="admin-dashboard.php" class="navbar-admin-link">Admin</a>
      <a href="checkout.html" class="navbar-cart-btn" id="cartBtn" aria-label="View cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" stroke="#1a1a1a" fill="none" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        <span class="cart-badge" id="cartBadge"></span>
      </a>
    </div>
  </div>
</nav>
<div class="back-bar">
  <div class="container">
    <a href="admin-dashboard.php" class="back-link">&#8592; Back to Admin Dashboard</a>
  </div>
</div>

<div class="admin-page">
  <div class="admin-container">

    <div class="admin-page-header">
      <p class="admin-breadcrumb">
        <a href="admin-dashboard.php">&#x2190; Admin Dashboard</a> &nbsp;/&nbsp; Modify Product
      </p>
      <h1>&#x270F; Modify Existing Product</h1>
      <p>Select a product below to edit its details, image, price, or artisan information.</p>
    </div>

    <?php if ($success): ?>
    <div class="success-banner visible">
      <span style="font-size:20px;">&#x2705;</span>
      <span><?php echo htmlspecialchars($success); ?></span>
    </div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="success-banner visible" style="background:#fdecea;color:#901C24;border-color:#f5c6cb;">
      <span style="font-size:20px;">&#x26A0;</span>
      <span><?php echo htmlspecialchars($error); ?></span>
    </div>
    <?php endif; ?>

    <div class="admin-search-bar">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="productSearch" placeholder="Search by name, artisan, or category..." oninput="filterProducts()" aria-label="Search products" />
    </div>
    <div class="admin-filter-btns">
      <button class="admin-filter-btn active" onclick="filterByCategory('all',this)">All</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Textiles',this)">Textiles</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Metalwork',this)">Metalwork</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Pottery',this)">Pottery</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Leather',this)">Leather</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Fragrance',this)">Fragrance</button>
      <button class="admin-filter-btn" onclick="filterByCategory('Wood',this)">Wood</button>
    </div>

    <div id="noResults" style="display:none;text-align:center;padding:40px;color:#888;font-size:14px;">No products found.</div>

    <div class="product-grid" id="productsGrid">
      <?php foreach ($products as $p): ?>
      <div class="product-card"
           id="card-<?php echo $p['pid']; ?>"
           data-search="<?php echo strtolower(htmlspecialchars($p['name'] . ' ' . $p['artisan'] . ' ' . $p['category'])); ?>"
           data-category="<?php echo htmlspecialchars($p['category']); ?>">
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
          <button class="edit-btn" onclick="openEdit(
  <?php echo htmlspecialchars(json_encode($p['pid']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($p['name']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($p['artisan']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($p['material'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode((string)$p['price']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($p['category']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($p['image']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($p['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode((int)($p['quantity'] ?? 0)), ENT_QUOTES, 'UTF-8'); ?>
)">— EDIT PRODUCT</button>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($products)): ?>
      <p style="text-align:center;padding:40px;color:#888;">No products in the database yet.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<!-- EDIT MODAL -->
<div class="edit-overlay" id="editOverlay" onclick="if(event.target===this)closeEdit()">
  <div class="edit-modal">
    <button class="edit-modal-close" onclick="closeEdit()" aria-label="Close">&times;</button>
    <h2 id="editModalTitle">&#x270F; Edit Product</h2>

    <form id="editForm" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="pid" id="editPid" />
      <input type="hidden" name="currentImg" id="currentImgPath" />

      <p class="form-section-title">Product Image</p>
      <div class="current-image-preview">
        <img id="currentImg" src="" alt="Current product image" />
        <p class="current-image-label">Current Image</p>
      </div>
      <div class="image-upload-area" id="editUploadArea">
        <input type="file" name="editProductImage" id="editProductImage" accept="image/*" onchange="previewEditImage(this)" />
        <div id="editUploadDefault">
          <p class="upload-text"><strong>Click to replace image</strong> or drag &amp; drop</p>
          <p class="upload-hint">JPG, PNG, WEBP &mdash; Max 5MB</p>
        </div>
        <div class="new-image-preview" id="editNewImgPreview" style="display:none;">
          <img id="editNewImg" src="" alt="New image preview" />
          <p class="image-preview-label" style="margin-top:8px;color:var(--gold);">&#x2713; New image selected</p>
        </div>
      </div>

      <p class="form-section-title">Basic Information</p>
      <div class="form-row">
        <div class="form-group">
          <label for="editName">Product Name</label>
          <input type="text" id="editName" name="editName" />
        </div>
        <div class="form-group">
          <label for="editRegion">Material</label>
          <input type="text" id="editRegion" name="editRegion" />
        </div>
      </div>
      <div class="form-group">
        <label for="editDesc">Description</label>
        <textarea id="editDesc" name="editDesc"></textarea>
      </div>

      <p class="form-section-title">Pricing &amp; Category</p>
      <div class="form-row">
        <div class="form-group">
          <label for="editPrice">Price (SAR)</label>
          <input type="number" id="editPrice" name="editPrice" min="1" />
        </div>
        <div class="form-group">
          <label for="editCategory">Category</label>
          <select id="editCategory" name="editCategory">
            <option value="Textiles">Textiles</option>
            <option value="Metalwork">Metalwork</option>
            <option value="Pottery">Pottery</option>
            <option value="Leather">Leather</option>
            <option value="Fragrance">Fragrance</option>
            <option value="Wood">Wood</option>
          </select>
        </div>
      </div>

      <p class="form-section-title">Artisan &amp; Stock</p>
      <div class="form-row">
        <div class="form-group">
          <label for="editArtisan">Artisan Name</label>
          <input type="text" id="editArtisan" name="editArtisan" />
        </div>
        <div class="form-group">
          <label for="editQty">Stock Quantity</label>
          <input type="number" id="editQty" name="editQty" min="0" />
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn-save" onclick="saveEdit()">&#x2705; Save Changes</button>
        <button type="button" class="btn-cancel-modal" onclick="closeEdit()">Cancel</button>
        <button type="button" class="btn-delete-prominent" onclick="confirmDelete()">🗑&nbsp; Delete Product</button>
      </div>
    </form>

    <!-- Hidden delete form -->
    <form id="deleteForm" method="POST" style="display:none;">
      <input type="hidden" name="action" value="delete" />
      <input type="hidden" name="pid" id="deletePid" />
    </form>
  </div>
</div>

<!-- Delete confirm -->
<div class="delete-confirm-overlay" id="deleteConfirmOverlay">
  <div class="delete-confirm-box">
    <h3>&#x26A0; Delete Product?</h3>
    <p>This action cannot be undone. Are you sure you want to permanently delete this product?</p>
    <div class="delete-actions">
      <button class="btn-confirm-delete" onclick="doDelete()">&#x1F5D1; Yes, Delete</button>
      <button class="btn-cancel-delete" onclick="document.getElementById('deleteConfirmOverlay').style.display='none'">Cancel</button>
    </div>
  </div>
</div>

<div class="success-toast" id="successToast">&#x2705; &nbsp; Product updated successfully!</div>

<footer class="footer">
  <div class="container">
    <div class="footer-bottom" style="text-align:center;padding:20px 0;">
      <p>&copy; 2026 Dar Al Hiraf &mdash; Admin Portal.</p>
    </div>
  </div>
</footer>

<script>
  var _cat = 'all';

  function openEdit(pid, name, artisan, material, price, category, img, desc, qty) {
    document.getElementById('editPid').value        = pid;
    document.getElementById('deletePid').value      = pid;
    document.getElementById('editModalTitle').textContent = '\u270F Edit: ' + name;
    document.getElementById('editName').value       = name;
    document.getElementById('editArtisan').value    = artisan;
    document.getElementById('editRegion').value     = material;
    document.getElementById('editPrice').value      = price;
    document.getElementById('editCategory').value   = category;
    document.getElementById('editDesc').value       = desc;
    document.getElementById('editQty').value        = qty;
    document.getElementById('currentImg').src       = img;
    document.getElementById('currentImgPath').value = img;
    document.getElementById('editNewImgPreview').style.display = 'none';
    document.getElementById('editUploadDefault').style.display = 'block';
    document.getElementById('editProductImage').value = '';
    document.getElementById('editOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeEdit() {
    document.getElementById('editOverlay').classList.remove('open');
    document.body.style.overflow = '';
  }

  function previewEditImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('editNewImg').src = e.target.result;
        document.getElementById('editUploadDefault').style.display = 'none';
        document.getElementById('editNewImgPreview').style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function saveEdit() {
    var name    = document.getElementById('editName').value.trim();
    var price   = document.getElementById('editPrice').value;
    var cat     = document.getElementById('editCategory').value;
    var artisan = document.getElementById('editArtisan').value.trim();
    if (!name || !price || !cat || !artisan) {
      alert('Please fill in all required fields.');
      return;
    }
    document.getElementById('editForm').submit();
  }

  function confirmDelete() {
    document.getElementById('deleteConfirmOverlay').style.display = 'flex';
  }

  function doDelete() {
    document.getElementById('deleteForm').submit();
  }

  function filterProducts() {
    var q = document.getElementById('productSearch').value.toLowerCase();
    var cards = document.querySelectorAll('.product-card'), vis = 0;
    cards.forEach(function(c) {
      var ok = (!q || c.dataset.search.includes(q)) && (_cat === 'all' || c.dataset.category === _cat);
      c.style.display = ok ? '' : 'none';
      if (ok) vis++;
    });
    document.getElementById('noResults').style.display = vis ? 'none' : 'block';
  }

  function filterByCategory(cat, btn) {
    _cat = cat;
    document.querySelectorAll('.admin-filter-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    filterProducts();
  }

  document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeEdit(); });
</script>
<script src="cart.js"></script>
</body>
</html>