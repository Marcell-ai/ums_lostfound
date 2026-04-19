<?php
// ============================================================
// db_config.php - Database Connection
// UMS Lost & Found Hub
// ============================================================

$host   = "localhost";
$user   = "root";
$pass   = "";               // Default XAMPP password is empty
$dbname = "lost_found_db";

// Create connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8 for proper encoding
mysqli_set_charset($conn, "utf8");

// Define upload folder path
define('UPLOAD_DIR', 'uploads/');

// Create uploads folder if it doesn't exist
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
?>
