<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'fish_store_db');

define('UPLOAD_IMAGE_DIR', __DIR__ . '/../uploads/images/');
define('UPLOAD_VIDEO_DIR', __DIR__ . '/../uploads/videos/');

define('BASE_URL', '/fish-store');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die('Database connection failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// ... rest of the code remains the same, but with placeholders for credentials