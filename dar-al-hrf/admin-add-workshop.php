<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}
require 'db.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['workshopTitle']    ?? '');
    $category = trim($_POST['workshopCategory'] ?? '');
    $artisan  = trim($_POST['workshopArtisan']  ?? '');
    $desc     = trim($_POST['workshopDesc']     ?? '');
    $location = trim($_POST['workshopLocation'] ?? '');
    $duration = trim($_POST['workshopDuration'] ?? '');
    $seats    = intval($_POST['workshopSeats']  ?? 0);
    $price    = trim($_POST['workshopPrice']    ?? '');
    $admin_id = $_SESSION['admin_id'];

    // Build sessions string from posted date/time pairs
    $sessions = [];
    $i = 1;
    while (isset($_POST['session' . $i . '-date']) && $_POST['session' . $i . '-date'] !== '') {
        $date = $_POST['session' . $i . '-date'];
        $time = $_POST['session' . $i . '-time'] ?? '';
        if ($date) {
            $dt = new DateTime($date . ' ' . $time);
            $sessions[] = $dt->format('M d Y g:iA');
        }
        $i++;
    }
    $sessionsStr = implode('|', $sessions);

    // Validate required fields
    if (!$name || !$category || !$artisan || !$desc || !$location || !$duration || !$seats || !$price) {
        $error = 'Please fill in all required fields.';
    } elseif (!isset($_FILES['workshopImage']) || $_FILES['workshopImage']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please upload a workshop image.';
    } else {
        $file    = $_FILES['workshopImage'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed)) {
            $error = 'Image must be JPG, PNG, or WEBP.';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $error = 'Image must be under 5MB.';
        } else {
            $filename  = 'workshop_' . time() . '_' . preg_replace('/[^a-z0-9]/', '', strtolower($name)) . '.' . $ext;
            $uploadDir = __DIR__ . '/images/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                $stmt = $pdo->prepare("
                    INSERT INTO products (name, category, description, price, artisan, image, location, duration, sessions, seats, admin_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $name, 'Workshop', $desc, $price, $artisan,
                    'images/' . $filename, $location,
                    $duration . ' Hours', $sessionsStr, $seats, $admin_id
                ]);
                $success = 'Workshop added successfully! It is now visible on the Workshops page.';
            } else {
                $error = 'Failed to upload image. Check that the images/ folder exists and is writable.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dar Al Hiraf — Add Workshop</title>
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
      <a href="workshops.html">Workshops</a>
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
        <a href="admin-dashboard.php">&#x2190; Admin Dashboard</a> &nbsp;/&nbsp; Add Workshop
      </p>
      <h1>&#x1F3DB; Add New Workshop</h1>
      <p>Create a new craft workshop session with all the details participants will need to book.</p>
    </div>

    <?php if ($success): ?>
    <div class="success-banner visible" id="successBanner">
      <span style="font-size:20px;">&#x2705;</span>
      <span><?php echo htmlspecialchars($success); ?></span>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="success-banner visible" id="errorBanner" style="background:#fdecea;color:#901C24;border-color:#f5c6cb;">
      <span style="font-size:20px;">&#x26A0;</span>
      <span><?php echo htmlspecialchars($error); ?></span>
    </div>
    <?php endif; ?>

    <div class="admin-form-card">
      <form id="workshopForm" method="POST" enctype="multipart/form-data">

        <p class="form-section-title">Workshop Banner Image</p>
        <div class="form-group">
          <label>Upload Workshop Photo <span class="req">*</span></label>
          <div class="image-upload-area" id="uploadArea">
            <input type="file" id="workshopImage" name="workshopImage" accept="image/*" onchange="previewImage(this)" />
            <div id="uploadDefault">
              <div class="upload-icon">&#x1F4F8;</div>
              <p class="upload-text"><strong>Click to upload</strong> or drag &amp; drop</p>
              <p class="upload-hint">JPG, PNG, WEBP &mdash; Max 5MB &mdash; Recommended 900&times;600px</p>
            </div>
            <div id="uploadPreviewInner" style="display:none;">
              <img id="previewImg" src="" alt="Preview" />
              <p class="image-preview-label">&#x2713; Image ready to upload</p>
            </div>
          </div>
        </div>

        <p class="form-section-title">Workshop Details</p>
        <div class="form-row">
          <div class="form-group">
            <label for="workshopTitle">Workshop Title (English) <span class="req">*</span></label>
            <input type="text" id="workshopTitle" name="workshopTitle" placeholder="e.g. Traditional Palm Weaving" />
          </div>
          <div class="form-group">
            <label for="workshopTitleAr">Workshop Title (Arabic)</label>
            <input type="text" id="workshopTitleAr" name="workshopTitleAr" placeholder="e.g. ورشة الخوص التقليدية" dir="rtl" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="workshopCategory">Craft Category <span class="req">*</span></label>
            <select id="workshopCategory" name="workshopCategory">
              <option value="">Select category...</option>
              <option>Basket Weaving</option>
              <option>Textiles</option>
              <option>Metalwork</option>
              <option>Pottery</option>
              <option>Leather</option>
              <option>Woodwork</option>
              <option>Fragrance</option>
              <option>Calligraphy</option>
            </select>
          </div>
          <div class="form-group">
            <label for="workshopArtisan">Lead Artisan <span class="req">*</span></label>
            <input type="text" id="workshopArtisan" name="workshopArtisan" placeholder="e.g. Fatima Al-Ghamdi" />
          </div>
        </div>
        <div class="form-group">
          <label for="workshopDesc">Description <span class="req">*</span></label>
          <textarea id="workshopDesc" name="workshopDesc" placeholder="Describe what participants will learn..."></textarea>
        </div>

        <p class="form-section-title">Location &amp; Logistics</p>
        <div class="form-group">
          <label for="workshopLocation">Full Location / Venue <span class="req">*</span></label>
          <input type="text" id="workshopLocation" name="workshopLocation" placeholder="e.g. Al-Ahsa Heritage Center, Al-Ahsa, Eastern Province" />
        </div>
        <div class="form-row-3">
          <div class="form-group">
            <label for="workshopDuration">Duration (hours) <span class="req">*</span></label>
            <input type="number" id="workshopDuration" name="workshopDuration" placeholder="e.g. 3" min="1" max="12" />
          </div>
          <div class="form-group">
            <label for="workshopSeats">Available Seats <span class="req">*</span></label>
            <input type="number" id="workshopSeats" name="workshopSeats" placeholder="e.g. 12" min="1" />
          </div>
          <div class="form-group">
            <label for="workshopPrice">Price per Person (SAR) <span class="req">*</span></label>
            <input type="number" id="workshopPrice" name="workshopPrice" placeholder="e.g. 280" min="1" />
          </div>
        </div>

        <p class="form-section-title">Available Sessions</p>
        <div class="sessions-box" id="sessionsBox">
          <div class="session-row" id="session-1">
            <div class="form-group" style="margin:0">
              <label>Date</label>
              <input type="date" id="session1-date" name="session1-date" />
            </div>
            <div class="form-group" style="margin:0">
              <label>Start Time</label>
              <input type="time" id="session1-time" name="session1-time" />
            </div>
            <button type="button" class="remove-btn" onclick="removeSession('session-1')" title="Remove session">&times;</button>
          </div>
        </div>
        <button type="button" class="add-session-btn" onclick="addSession()">&#x2B; Add Another Date / Time</button>

        <div class="form-actions">
          <button type="button" class="btn-submit" onclick="submitWorkshop()">&#x1F3DB; Add Workshop</button>
          <a href="admin-dashboard.php" class="btn-cancel">Cancel</a>
        </div>

      </form>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="container">
    <div class="footer-bottom" style="text-align:center;padding:20px 0;">
      <p>&copy; 2026 Dar Al Hiraf &mdash; Admin Portal.</p>
    </div>
  </div>
</footer>

<script>
  var sessionCount = 1;

  function previewImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadDefault').style.display = 'none';
        document.getElementById('uploadPreviewInner').style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function addSession() {
    sessionCount++;
    var id  = 'session-' + sessionCount;
    var box = document.getElementById('sessionsBox');
    var row = document.createElement('div');
    row.className = 'session-row';
    row.id = id;
    row.innerHTML =
      '<div class="form-group" style="margin:0"><label>Date</label>' +
      '<input type="date" id="' + id + '-date" name="session' + sessionCount + '-date" /></div>' +
      '<div class="form-group" style="margin:0"><label>Start Time</label>' +
      '<input type="time" id="' + id + '-time" name="session' + sessionCount + '-time" /></div>' +
      '<button type="button" class="remove-btn" onclick="removeSession(\'' + id + '\')" title="Remove">&times;</button>';
    box.appendChild(row);
  }

  function removeSession(id) {
    var el = document.getElementById(id);
    if (el) el.remove();
  }

  function submitWorkshop() {
    var title    = document.getElementById('workshopTitle').value.trim();
    var cat      = document.getElementById('workshopCategory').value;
    var artisan  = document.getElementById('workshopArtisan').value.trim();
    var desc     = document.getElementById('workshopDesc').value.trim();
    var loc      = document.getElementById('workshopLocation').value.trim();
    var dur      = document.getElementById('workshopDuration').value;
    var seats    = document.getElementById('workshopSeats').value;
    var price    = document.getElementById('workshopPrice').value;
    var img      = document.getElementById('workshopImage').files.length;

    if (!title || !cat || !artisan || !desc || !loc || !dur || !seats || !price || !img) {
      alert('Please fill in all required fields and upload a workshop image.');
      return;
    }
    document.getElementById('workshopForm').submit();
  }
</script>
<script src="cart.js"></script>
</body>
</html>