<?php
// ============================================================
// edit.php - UPDATE Form (Member 3: Modification Lead)
// Fetches existing record and handles UPDATE with image replace
// ============================================================
include 'db_config.php';

$success = '';
$error   = '';

// ---- Step 1: Validate the ID from URL ----
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);  // Cast to integer for safety

// ---- Step 2: Fetch the existing record ----
$fetch_sql = "SELECT * FROM items WHERE id = $id";
$fetch_res = mysqli_query($conn, $fetch_sql);

if (mysqli_num_rows($fetch_res) === 0) {
    // Record not found, redirect to dashboard
    header("Location: dashboard.php");
    exit();
}

$item = mysqli_fetch_assoc($fetch_res);

// ---- Step 3: Handle the UPDATE form submission ----
if (isset($_POST['submit'])) {

    // Sanitize all inputs
    $item_name     = mysqli_real_escape_string($conn, trim($_POST['item_name']));
    $description   = mysqli_real_escape_string($conn, trim($_POST['description']));
    $category      = mysqli_real_escape_string($conn, trim($_POST['category']));
    $location      = mysqli_real_escape_string($conn, trim($_POST['location']));
    $date_reported = mysqli_real_escape_string($conn, trim($_POST['date_reported']));
    $contact_name  = mysqli_real_escape_string($conn, trim($_POST['contact_name']));
    $contact_email = mysqli_real_escape_string($conn, trim($_POST['contact_email']));
    $contact_phone = mysqli_real_escape_string($conn, trim($_POST['contact_phone']));
    $status        = mysqli_real_escape_string($conn, trim($_POST['status']));

    // Validate required fields
    if (empty($item_name) || empty($description) || empty($category) || empty($location) ||
        empty($date_reported) || empty($contact_name) || empty($contact_email) || empty($contact_phone)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        // Keep existing image by default
        $image_path = $item['image_path'];

        // Handle new image upload (replacement)
        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['item_image']['tmp_name'];
            $file_name = $_FILES['item_image']['name'];
            $file_size = $_FILES['item_image']['size'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($file_ext, $allowed_ext)) {
                $error = "Invalid image type. Only JPG, PNG, GIF, and WEBP are allowed.";
            } elseif ($file_size > 5 * 1024 * 1024) {
                $error = "Image file is too large. Maximum allowed is 5MB.";
            } else {
                $new_filename = 'item_' . time() . '_' . uniqid() . '.' . $file_ext;
                $upload_path  = UPLOAD_DIR . $new_filename;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Delete old image file if it exists
                    if (!empty($item['image_path']) && file_exists($item['image_path'])) {
                        unlink($item['image_path']);
                    }
                    $image_path = $upload_path;
                } else {
                    $error = "Failed to upload new image. Please try again.";
                }
            }
        }

        // Remove image if checkbox ticked
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            if (!empty($item['image_path']) && file_exists($item['image_path'])) {
                unlink($item['image_path']);
            }
            $image_path = NULL;
        }

        if (empty($error)) {
            $img_val = ($image_path !== NULL) ? "'$image_path'" : "NULL";

            $sql = "UPDATE items SET
                        item_name     = '$item_name',
                        description   = '$description',
                        category      = '$category',
                        location      = '$location',
                        date_reported = '$date_reported',
                        contact_name  = '$contact_name',
                        contact_email = '$contact_email',
                        contact_phone = '$contact_phone',
                        image_path    = $img_val,
                        status        = '$status'
                    WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                $success = "Record updated successfully!";
                // Re-fetch the updated record to reflect changes in form
                $item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM items WHERE id = $id"));
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
    <title>Edit Item — UMS Lost &amp; Found Hub</title>
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
            <a href="add_item.php" class="nav-link nav-cta">Report Item</a>
        </div>
    </nav>

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header-inner">
            <h1 class="page-title">Edit Report</h1>
            <p class="page-sub">Update the details for: <strong><?php echo htmlspecialchars($item['item_name']); ?></strong></p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="main-container">
        <div class="form-wrap">

            <!-- Alerts -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    ✅ <?php echo $success; ?>
                    <a href="dashboard.php" class="alert-link">Back to Dashboard →</a>
                </div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">❌ <?php echo $error; ?></div>
            <?php endif; ?>

            <!-- EDIT FORM -->
            <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="crud-form">

                <div class="form-section-title">📦 Item Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="item_name">Item Name <span class="req">*</span></label>
                        <input type="text" id="item_name" name="item_name" class="form-control"
                               value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category <span class="req">*</span></label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="Lost"  <?php echo ($item['category']=='Lost'  ? 'selected' : ''); ?>>🔴 Lost</option>
                            <option value="Found" <?php echo ($item['category']=='Found' ? 'selected' : ''); ?>>🟢 Found</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description <span class="req">*</span></label>
                    <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location <span class="req">*</span></label>
                        <input type="text" id="location" name="location" class="form-control"
                               value="<?php echo htmlspecialchars($item['location']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="date_reported">Date <span class="req">*</span></label>
                        <input type="date" id="date_reported" name="date_reported" class="form-control"
                               value="<?php echo $item['date_reported']; ?>" required>
                    </div>
                </div>

                <!-- Current Image Display -->
                <div class="form-group">
                    <label>Current Image</label>
                    <?php if (!empty($item['image_path']) && file_exists($item['image_path'])): ?>
                        <div class="current-img-wrap">
                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Current Image" class="current-img">
                            <label class="remove-img-label">
                                <input type="checkbox" name="remove_image" value="1">
                                Remove current image
                            </label>
                        </div>
                    <?php else: ?>
                        <p class="no-img-text">No image currently attached.</p>
                    <?php endif; ?>
                </div>

                <!-- New Image Upload -->
                <div class="form-group">
                    <label for="item_image">Replace / Add Photo <span class="optional">(optional — max 5MB)</span></label>
                    <div class="file-upload-wrap">
                        <input type="file" id="item_image" name="item_image"
                               class="file-input" accept=".jpg,.jpeg,.png,.gif,.webp"
                               onchange="previewImage(event)">
                        <label for="item_image" class="file-label">📷 Choose New Photo</label>
                    </div>
                    <div id="img-preview-wrap" style="display:none; margin-top:12px;">
                        <img id="img-preview" src="" alt="Preview" class="upload-preview">
                    </div>
                </div>

                <div class="form-section-title">📞 Contact Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_name">Your Name <span class="req">*</span></label>
                        <input type="text" id="contact_name" name="contact_name" class="form-control"
                               value="<?php echo htmlspecialchars($item['contact_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_phone">Phone Number <span class="req">*</span></label>
                        <input type="tel" id="contact_phone" name="contact_phone" class="form-control"
                               value="<?php echo htmlspecialchars($item['contact_phone']); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_email">Email Address <span class="req">*</span></label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control"
                               value="<?php echo htmlspecialchars($item['contact_email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="Active"   <?php echo ($item['status']=='Active'   ? 'selected' : ''); ?>>🟡 Active</option>
                            <option value="Resolved" <?php echo ($item['status']=='Resolved' ? 'selected' : ''); ?>>✅ Resolved</option>
                        </select>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary">💾 Save Changes</button>
                    <a href="dashboard.php" class="btn btn-outline">← Cancel</a>
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

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('img-preview').src = e.target.result;
                    document.getElementById('img-preview-wrap').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>
</html>
