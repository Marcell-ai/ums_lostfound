<?php
// ============================================================
// delete.php - DELETE Logic (Member 4: Security & Maintenance)
// Handles safe deletion with input sanitization & image cleanup
// ============================================================
include 'db_config.php';

// ---- Security: Validate the ID ----
// Only accept numeric GET parameter; reject anything else
if (!isset($_GET['id']) || !ctype_digit(strval($_GET['id']))) {
    // Invalid or missing ID — redirect silently
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);  // Cast to integer (prevents SQL injection)

// ---- Fetch record to verify it exists & get image path ----
$check_sql = "SELECT id, item_name, image_path FROM items WHERE id = $id LIMIT 1";
$check_res = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_res) === 0) {
    // Record doesn't exist — redirect
    header("Location: dashboard.php?msg=notfound");
    exit();
}

$record    = mysqli_fetch_assoc($check_res);
$item_name = $record['item_name'];
$img_path  = $record['image_path'];

// ---- Execute DELETE ----
$delete_sql = "DELETE FROM items WHERE id = $id";

if (mysqli_query($conn, $delete_sql)) {

    // Clean up: Delete the associated image file if it exists
    if (!empty($img_path) && file_exists($img_path)) {
        unlink($img_path);  // Remove file from server
    }

    // Redirect to dashboard with a success message via URL parameter
    header("Location: dashboard.php?deleted=" . urlencode($item_name));
    exit();

} else {
    // On database error, show error page
    $db_error = mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Error — UMS Lost &amp; Found Hub</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <a href="index.php" style="text-decoration:none; display:flex; align-items:center; gap:8px;">
                <span class="brand-ums">UMS</span>
                <span class="brand-sep">|</span>
                <span class="brand-title">Lost &amp; Found Hub</span>
            </a>
        </div>
        <div class="nav-links">
            <a href="dashboard.php" class="nav-link">← Back to Dashboard</a>
        </div>
    </nav>
    <main class="main-container" style="text-align:center; padding: 80px 20px;">
        <div class="alert alert-error" style="max-width:500px; margin: 0 auto;">
            <h2>⚠️ Deletion Failed</h2>
            <p>An error occurred while trying to delete this record.</p>
            <p><small><?php echo htmlspecialchars($db_error); ?></small></p>
            <a href="dashboard.php" class="btn btn-primary" style="margin-top:16px;">← Return to Dashboard</a>
        </div>
    </main>
</body>
</html>
