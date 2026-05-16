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
    $stmt = $pdo->prepare("DELETE FROM products WHERE pid = ? AND category = 'Workshop'");
    $stmt->execute([$pid]);
    $success = 'Workshop deleted successfully.';
}

// ── UPDATE ────────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $pid      = intval($_POST['pid']);
    $name     = trim($_POST['editTitle']    ?? '');
    $artisan  = trim($_POST['editArtisan']  ?? '');
    $category = trim($_POST['editCategory'] ?? '');
    $desc     = trim($_POST['editDesc']     ?? '');
    $location = trim($_POST['editLocation'] ?? '');
    $duration = trim($_POST['editDuration'] ?? '');
    $seats    = intval($_POST['editSeats']  ?? 0);
    $price    = trim($_POST['editPrice']    ?? '');
    $currentImg = trim($_POST['currentImg'] ?? '');

    if (!$name || !$artisan || !$location || !$duration || !$seats || !$price) {
        $error = 'Please fill in all required fields.';
    } else {
        $newImg = $currentImg;

        if (isset($_FILES['editWorkshopImage']) && $_FILES['editWorkshopImage']['error'] === UPLOAD_ERR_OK) {
            $file    = $_FILES['editWorkshopImage'];
            $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Image must be JPG, PNG, or WEBP.';
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $error = 'Image must be under 5MB.';
            } else {
                $filename  = 'workshop_' . time() . '_' . preg_replace('/[^a-z0-9]/', '', strtolower($name)) . '.' . $ext;
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
                SET name=?, artisan=?, description=?, price=?, image=?, location=?, duration=?, seats=?
                WHERE pid=? AND category='Workshop'
            ");
            $stmt->execute([
                $name, $artisan, $desc, $price,
                $newImg, $location, $duration . ' Hours', $seats, $pid
            ]);
            $success = 'Workshop updated successfully.';
        }
    }
}

// ── FETCH ALL WORKSHOPS ───────────────────────────────────────────────────────
$workshops = $pdo->query("SELECT * FROM products WHERE category = 'Workshop' ORDER BY pid ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Modify Workshop</title>
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
        <a href="admin-dashboard.php">&#x2190; Admin Dashboard</a> &nbsp;/&nbsp; Modify Workshop
      </p>
      <h1>&#x1F527; Modify Existing Workshop</h1>
      <p>Select a workshop below to edit its details, image, dates, seats, or pricing.</p>
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

    <div class="workshops-admin-grid">
      <?php foreach ($workshops as $w): ?>
      <div class="workshop-admin-card">
        <div class="wac-img"><img src="<?php echo htmlspecialchars($w['image']); ?>" alt="<?php echo htmlspecialchars($w['name']); ?>" /></div>
        <div class="wac-body">
          <p class="wac-tag"><?php echo htmlspecialchars($w['category']); ?></p>
          <p class="wac-title"><?php echo htmlspecialchars($w['name']); ?></p>
          <p class="wac-meta">&#128205; <?php echo htmlspecialchars($w['location'] ?? ''); ?></p>
          <p class="wac-meta">&#8987; <?php echo htmlspecialchars($w['duration'] ?? ''); ?> &nbsp;|&nbsp; <?php echo (int)($w['seats'] ?? 0); ?> seats</p>
          <p class="wac-price"><small>SAR</small> <?php echo number_format($w['price'], 0); ?></p>
          <button class="wac-btn" onclick="openEdit(
  <?php echo htmlspecialchars(json_encode($w['pid']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($w['name']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($w['artisan']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($w['location'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode(str_replace(' Hours', '', $w['duration'] ?? '')), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode((int)($w['seats'] ?? 0)), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode((string)$w['price']), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($w['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>,
  <?php echo htmlspecialchars(json_encode($w['image']), ENT_QUOTES, 'UTF-8'); ?>
)">&#x270F; Edit Workshop</button>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($workshops)): ?>
      <p style="text-align:center;padding:40px;color:#888;width:100%;">No workshops in the database yet.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<!-- EDIT MODAL -->
<div class="edit-overlay" id="editOverlay" onclick="if(event.target===this)closeEdit()">
  <div class="edit-modal">
    <button class="edit-modal-close" onclick="closeEdit()" aria-label="Close">&times;</button>
    <h2 id="editModalTitle">&#x270F; Edit Workshop</h2>

    <form id="editForm" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="update" />
      <input type="hidden" name="pid" id="editPid" />
      <input type="hidden" name="currentImg" id="currentImgPath" />

      <p class="form-section-title">Workshop Banner Image</p>
      <div class="current-image-preview">
        <img id="currentImg" src="" alt="Current workshop image" />
        <p class="current-image-label">Current Image</p>
      </div>
      <div class="image-upload-area" id="editUploadArea">
        <input type="file" name="editWorkshopImage" id="editWorkshopImage" accept="image/*" onchange="previewEditImage(this)" />
        <div id="editUploadDefault">
          <p class="upload-text"><strong>Click to replace image</strong> or drag &amp; drop</p>
          <p class="upload-hint">JPG, PNG, WEBP &mdash; Max 5MB</p>
        </div>
        <div class="new-image-preview" id="editNewImgPreview" style="display:none;">
          <img id="editNewImg" src="" alt="New image preview" />
          <p class="image-preview-label" style="margin-top:8px;color:var(--gold);">&#x2713; New image selected</p>
        </div>
      </div>

      <p class="form-section-title">Workshop Details</p>
      <div class="form-row">
        <div class="form-group">
          <label for="editTitle">Title (English)</label>
          <input type="text" id="editTitle" name="editTitle" />
        </div>
        <div class="form-group">
          <label for="editArtisan">Lead Artisan</label>
          <input type="text" id="editArtisan" name="editArtisan" />
        </div>
      </div>
      <div class="form-group">
        <label for="editDesc">Description</label>
        <textarea id="editDesc" name="editDesc"></textarea>
      </div>

      <p class="form-section-title">Location &amp; Logistics</p>
      <div class="form-group">
        <label for="editLocation">Full Location / Venue</label>
        <input type="text" id="editLocation" name="editLocation" />
      </div>
      <div class="form-row-3">
        <div class="form-group">
          <label for="editDuration">Duration (hours)</label>
          <input type="number" id="editDuration" name="editDuration" min="1" max="12" />
        </div>
        <div class="form-group">
          <label for="editSeats">Available Seats</label>
          <input type="number" id="editSeats" name="editSeats" min="1" />
        </div>
        <div class="form-group">
          <label for="editPrice">Price (SAR)</label>
          <input type="number" id="editPrice" name="editPrice" min="1" />
        </div>
      </div>

      <div class="form-actions">
        <button type="button" class="btn-save" onclick="saveEdit()">&#x2705; Save Changes</button>
        <button type="button" class="btn-cancel-modal" onclick="closeEdit()">Cancel</button>
        <button type="button" class="btn-delete-prominent" onclick="confirmDelete()">🗑&nbsp; Delete Workshop</button>
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
    <h3>&#x26A0; Delete Workshop?</h3>
    <p>This action cannot be undone. Are you sure you want to permanently delete this workshop?</p>
    <div class="delete-actions">
      <button class="btn-confirm-delete" onclick="doDelete()">&#x1F5D1; Yes, Delete</button>
      <button class="btn-cancel-delete" onclick="document.getElementById('deleteConfirmOverlay').style.display='none'">Cancel</button>
    </div>
  </div>
</div>

<div class="success-toast" id="successToast">&#x2705; &nbsp; Workshop updated successfully!</div>

<footer class="footer">
  <div class="container">
    <div class="footer-bottom" style="text-align:center;padding:20px 0;">
      <p>&copy; 2026 Dar Al Hiraf &mdash; Admin Portal.</p>
    </div>
  </div>
</footer>

<script>
  function openEdit(pid, name, artisan, location, duration, seats, price, desc, img) {
    document.getElementById('editPid').value        = pid;
    document.getElementById('deletePid').value      = pid;
    document.getElementById('editModalTitle').textContent = '\u270F Edit: ' + name;
    document.getElementById('editTitle').value      = name;
    document.getElementById('editArtisan').value    = artisan;
    document.getElementById('editLocation').value   = location;
    document.getElementById('editDuration').value   = duration;
    document.getElementById('editSeats').value      = seats;
    document.getElementById('editPrice').value      = price;
    document.getElementById('editDesc').value       = desc;
    document.getElementById('currentImg').src       = img;
    document.getElementById('currentImgPath').value = img;
    document.getElementById('editNewImgPreview').style.display = 'none';
    document.getElementById('editUploadDefault').style.display = 'block';
    document.getElementById('editWorkshopImage').value = '';
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
    var name     = document.getElementById('editTitle').value.trim();
    var artisan  = document.getElementById('editArtisan').value.trim();
    var location = document.getElementById('editLocation').value.trim();
    var duration = document.getElementById('editDuration').value;
    var seats    = document.getElementById('editSeats').value;
    var price    = document.getElementById('editPrice').value;
    if (!name || !artisan || !location || !duration || !seats || !price) {
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

  document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeEdit(); });
</script>
<script src="cart.js"></script>
</body>
</html>