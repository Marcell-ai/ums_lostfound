<?php
// ============================================================
// add_item.php - CREATE Form (Member 1: Logic Architect)
// Handles new report form and INSERT with image upload
// ============================================================
include 'db_config.php';

$success = '';
$error   = '';

if (isset($_POST['submit'])) {

    // ---- 1. Collect & Sanitize Inputs ----
    $item_name    = mysqli_real_escape_string($conn, trim($_POST['item_name']));
    $description  = mysqli_real_escape_string($conn, trim($_POST['description']));
    $category     = mysqli_real_escape_string($conn, trim($_POST['category']));
    $location     = mysqli_real_escape_string($conn, trim($_POST['location']));
    $date_reported = mysqli_real_escape_string($conn, trim($_POST['date_reported']));
    $contact_name  = mysqli_real_escape_string($conn, trim($_POST['contact_name']));
    $contact_email = mysqli_real_escape_string($conn, trim($_POST['contact_email']));
    $contact_phone = mysqli_real_escape_string($conn, trim($_POST['contact_phone']));
    $status        = 'Active';

    // ---- 2. Validate Required Fields ----
    if (empty($item_name) || empty($description) || empty($category) || empty($location) ||
        empty($date_reported) || empty($contact_name) || empty($contact_email) || empty($contact_phone)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        // ---- 3. Handle Image Upload ----
        $image_path = NULL;

        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['item_image']['tmp_name'];
            $file_name = $_FILES['item_image']['name'];
            $file_size = $_FILES['item_image']['size'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Allowed extensions
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($file_ext, $allowed_ext)) {
                $error = "Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed.";
            } elseif ($file_size > 5 * 1024 * 1024) {  // 5MB limit
                $error = "Image file is too large. Maximum allowed size is 5MB.";
            } else {
                // Generate unique filename to avoid conflicts
                $new_filename = 'item_' . time() . '_' . uniqid() . '.' . $file_ext;
                $upload_path  = UPLOAD_DIR . $new_filename;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $image_path = $upload_path;
                } else {
                    $error = "Failed to upload image. Please try again.";
                }
            }
        }

        // ---- 4. INSERT into Database (only if no upload error) ----
        if (empty($error)) {
            $img_val = ($image_path !== NULL) ? "'$image_path'" : "NULL";

            $sql = "INSERT INTO items 
                        (item_name, description, category, location, date_reported, contact_name, contact_email, contact_phone, image_path, status)
                    VALUES 
                        ('$item_name', '$description', '$category', '$location', '$date_reported', '$contact_name', '$contact_email', '$contact_phone', $img_val, '$status')";

            if (mysqli_query($conn, $sql)) {
                $success = "Report submitted successfully! Your item has been added to the directory.";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Item — UMS Lost &amp; Found Hub</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-brand">
            <a href="index.php" style="text-decoration:none; display:flex; align-items:center; gap:8px;">
                <span class="brand-ums">UMS</span>
                <span class="brand-sep">|</span>
                <span class="brand-title">Lost &amp; Found Hub</span>
            </a>
        </div>
        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="add_item.php" class="nav-link active nav-cta">Report Item</a>
        </div>
    </nav>

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header-inner">
            <h1 class="page-title">Report a Lost or Found Item</h1>
            <p class="page-sub">Fill in the details below. All fields marked with <span class="req">*</span> are required.</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="main-container">
        <div class="form-wrap">

            <!-- Success / Error Alerts -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    ✅ <?php echo $success; ?>
                    <a href="dashboard.php" class="alert-link">View Dashboard →</a>
                </div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    ❌ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- FORM -->
            <form action="add_item.php" method="POST" enctype="multipart/form-data" class="crud-form">

                <!-- Row 1: Item Info -->
                <div class="form-section-title">📦 Item Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="item_name">Item Name <span class="req">*</span></label>
                        <input type="text" id="item_name" name="item_name" class="form-control"
                               placeholder="e.g. Black Laptop Bag"
                               value="<?php echo isset($_POST['item_name']) ? htmlspecialchars($_POST['item_name']) : ''; ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category <span class="req">*</span></label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="" disabled selected>-- Select Category --</option>
                            <option value="Lost"  <?php echo (isset($_POST['category']) && $_POST['category']=='Lost'  ? 'selected' : ''); ?>>🔴 Lost</option>
                            <option value="Found" <?php echo (isset($_POST['category']) && $_POST['category']=='Found' ? 'selected' : ''); ?>>🟢 Found</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description <span class="req">*</span></label>
                    <textarea id="description" name="description" class="form-control" rows="4"
                              placeholder="Describe the item in detail — color, size, brand, distinguishing features..."
                              required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location <span class="req">*</span></label>
                        <input type="text" id="location" name="location" class="form-control"
                               placeholder="e.g. Library Level 2, Block B Cafeteria"
                               value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="date_reported">Date <span class="req">*</span></label>
                        <input type="date" id="date_reported" name="date_reported" class="form-control"
                               value="<?php echo isset($_POST['date_reported']) ? htmlspecialchars($_POST['date_reported']) : date('Y-m-d'); ?>"
                               required>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="form-group">
                    <label for="item_image">Item Photo <span class="optional">(optional — max 5MB, JPG/PNG/GIF/WEBP)</span></label>
                    <div class="file-upload-wrap">
                        <input type="file" id="item_image" name="item_image"
                               class="file-input" accept=".jpg,.jpeg,.png,.gif,.webp"
                               onchange="previewImage(event)">
                        <label for="item_image" class="file-label">
                            📷 Click to choose a photo
                        </label>
                    </div>
                    <div id="img-preview-wrap" style="display:none; margin-top:12px;">
                        <img id="img-preview" src="" alt="Preview" class="upload-preview">
                        <button type="button" class="btn-remove-img" onclick="removeImage()">✕ Remove</button>
                    </div>
                </div>

                <!-- Row 2: Contact Info -->
                <div class="form-section-title">📞 Contact Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_name">Your Name <span class="req">*</span></label>
                        <input type="text" id="contact_name" name="contact_name" class="form-control"
                               placeholder="Full Name"
                               value="<?php echo isset($_POST['contact_name']) ? htmlspecialchars($_POST['contact_name']) : ''; ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="contact_phone">Phone Number <span class="req">*</span></label>
                        <input type="tel" id="contact_phone" name="contact_phone" class="form-control"
                               placeholder="e.g. 0123456789"
                               value="<?php echo isset($_POST['contact_phone']) ? htmlspecialchars($_POST['contact_phone']) : ''; ?>"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contact_email">Email Address <span class="req">*</span></label>
                    <input type="email" id="contact_email" name="contact_email" class="form-control"
                           placeholder="e.g. name@student.ums.edu.my"
                           value="<?php echo isset($_POST['contact_email']) ? htmlspecialchars($_POST['contact_email']) : ''; ?>"
                           required>
                </div>

                <!-- Submit Buttons -->
                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary">📋 Submit Report</button>
                    <a href="dashboard.php" class="btn btn-outline">← Back to Dashboard</a>
                </div>

            </form>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand"><span class="brand-ums">UMS</span> Lost &amp; Found Hub</div>
            <p class="footer-sub">Universiti Malaysia Sabah | CP0025 Web Programming | 2026</p>
        </div>
    </footer>

    <!-- Image Preview Script -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('img-preview').src = e.target.result;
                    document.getElementById('img-preview-wrap').style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('item_image').value = '';
            document.getElementById('img-preview').src = '';
            document.getElementById('img-preview-wrap').style.display = 'none';
        }
    </script>

</body>
</html>
