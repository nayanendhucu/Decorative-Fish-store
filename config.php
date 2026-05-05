<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fish_store_db');

define('UPLOAD_IMAGE_DIR', __DIR__ . '/../uploads/images/');
define('UPLOAD_VIDEO_DIR', __DIR__ . '/../uploads/videos/');

define('BASE_URL', '/fish-store');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die('Database connection failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

$result = $mysqli->query("SHOW TABLES LIKE 'admin'");
if ($result && $result->num_rows === 1) {
    $countResult = $mysqli->query('SELECT COUNT(*) AS count FROM admin');
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        if ((int) $row['count'] === 0) {
            $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
            $insertAdmin = $mysqli->prepare('INSERT INTO admin (username, password_hash, created_at) VALUES (?, ?, NOW())');
            $adminUser = 'admin';
            $insertAdmin->bind_param('ss', $adminUser, $passwordHash);
            $insertAdmin->execute();
        }
    }
}

$mysqli->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS customer_id INT NULL AFTER id");
$mysqli->query("ALTER TABLE customers ADD COLUMN IF NOT EXISTS password_hash VARCHAR(255) NOT NULL AFTER email");
