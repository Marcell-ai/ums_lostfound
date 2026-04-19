<?php
// ============================================================
// dashboard.php - READ Dashboard (Member 2: Data Specialist)
// Displays all lost/found items with search and filter
// ============================================================
include 'db_config.php';

// --- Search & Filter Logic ---
$search   = isset($_GET['search'])   ? mysqli_real_escape_string($conn, trim($_GET['search']))   : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, trim($_GET['category'])) : '';
$status   = isset($_GET['status'])   ? mysqli_real_escape_string($conn, trim($_GET['status']))   : '';

// Build dynamic WHERE clause
$where = "WHERE 1=1";
if (!empty($search)) {
    $where .= " AND (item_name LIKE '%$search%' OR description LIKE '%$search%' OR location LIKE '%$search%' OR contact_name LIKE '%$search%')";
}
if (!empty($category)) {
    $where .= " AND category = '$category'";
}
if (!empty($status)) {
    $where .= " AND status = '$status'";
}

$sql    = "SELECT * FROM items $where ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$count  = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — UMS Lost &amp; Found Hub</title>
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
            <a href="dashboard.php" class="nav-link active">Dashboard</a>
            <a href="add_item.php" class="nav-link nav-cta">Report Item</a>
        </div>
    </nav>

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-header-inner">
            <h1 class="page-title">Item Dashboard</h1>
            <p class="page-sub">Browse and search all active lost &amp; found reports on campus.</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="main-container">

        <!-- Delete Success Notice (from delete.php redirect) -->
        <?php if (isset($_GET['deleted'])): ?>
            <div class="deleted-notice">
                ✅ Record "<strong><?php echo htmlspecialchars($_GET['deleted']); ?></strong>" was successfully deleted.
            </div>
        <?php endif; ?>

        <!-- SEARCH & FILTER BAR -->
        <div class="filter-bar">
            <form method="GET" action="dashboard.php" class="filter-form">
                <div class="filter-group">
                    <input
                        type="text"
                        name="search"
                        class="filter-input"
                        placeholder="🔍  Search by name, location, or description..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >
                </div>
                <div class="filter-group filter-selects">
                    <select name="category" class="filter-select">
                        <option value="">All Categories</option>
                        <option value="Lost"  <?php echo ($category=='Lost'  ? 'selected' : ''); ?>>Lost</option>
                        <option value="Found" <?php echo ($category=='Found' ? 'selected' : ''); ?>>Found</option>
                    </select>
                    <select name="status" class="filter-select">
                        <option value="">All Statuses</option>
                        <option value="Active"   <?php echo ($status=='Active'   ? 'selected' : ''); ?>>Active</option>
                        <option value="Resolved" <?php echo ($status=='Resolved' ? 'selected' : ''); ?>>Resolved</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="dashboard.php" class="btn btn-outline btn-sm">Clear</a>
                </div>
            </form>
        </div>

        <!-- RESULT COUNT & ADD BUTTON -->
        <div class="result-bar">
            <span class="result-count">
                <?php echo $count; ?> record<?php echo ($count != 1 ? 's' : ''); ?> found
                <?php if (!empty($search) || !empty($category) || !empty($status)): ?>
                    &mdash; <a href="dashboard.php" class="clear-link">clear filters</a>
                <?php endif; ?>
            </span>
            <a href="add_item.php" class="btn btn-primary btn-sm">+ Report New Item</a>
        </div>

        <!-- ITEMS TABLE -->
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Date Reported</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($count > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Determine badge class
                        $catClass    = strtolower($row['category']);
                        $statusClass = strtolower($row['status']);
                        echo "<tr class='item-row $statusClass-row'>";
                        echo "<td class='td-num'>" . $no++ . "</td>";

                        // Thumbnail
                        echo "<td class='td-img'>";
                        if (!empty($row['image_path']) && file_exists($row['image_path'])) {
                            echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Item Image' class='thumb'>";
                        } else {
                            echo "<div class='no-img'>No Image</div>";
                        }
                        echo "</td>";

                        echo "<td class='td-name'><strong>" . htmlspecialchars($row['item_name']) . "</strong><br><span class='td-desc'>" . htmlspecialchars(substr($row['description'], 0, 60)) . (strlen($row['description'])>60?'...':'') . "</span></td>";
                        echo "<td><span class='badge badge-" . $catClass . "'>" . htmlspecialchars($row['category']) . "</span></td>";
                        echo "<td>📍 " . htmlspecialchars($row['location']) . "</td>";
                        echo "<td>" . date('d M Y', strtotime($row['date_reported'])) . "</td>";
                        echo "<td class='td-contact'><strong>" . htmlspecialchars($row['contact_name']) . "</strong><br><a href='mailto:" . htmlspecialchars($row['contact_email']) . "' class='email-link'>" . htmlspecialchars($row['contact_email']) . "</a><br>" . htmlspecialchars($row['contact_phone']) . "</td>";
                        echo "<td><span class='badge badge-" . $statusClass . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td class='td-actions'>
                            <a href='edit.php?id=" . $row['id'] . "' class='btn-action btn-edit'>✏️ Edit</a>
                            <a href='delete.php?id=" . $row['id'] . "' class='btn-action btn-delete' onclick='return confirm(\"Are you sure you want to delete this record?\")'>🗑️ Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='empty-row'>No records found. <a href='dashboard.php'>Clear filters</a> or <a href='add_item.php'>add a new report</a>.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand"><span class="brand-ums">UMS</span> Lost &amp; Found Hub</div>
            <p class="footer-sub">Universiti Malaysia Sabah | CP0025 Web Programming | 2026</p>
        </div>
    </footer>

</body>
</html>
