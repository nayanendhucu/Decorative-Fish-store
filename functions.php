<?php
require_once __DIR__ . '/config.php';

function sanitize($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

function is_customer_logged_in() {
    return isset($_SESSION['customer_id']);
}

function require_admin() {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function require_customer() {
    if (!is_customer_logged_in()) {
        header('Location: ' . BASE_URL . '/customer/login.php');
        exit;
    }
}

function get_current_customer() {
    if (!is_customer_logged_in()) {
        return null;
    }
    global $mysqli;
    $stmt = $mysqli->prepare('SELECT * FROM customers WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $_SESSION['customer_id']);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function get_available_fishes() {
    global $mysqli;
    $stmt = $mysqli->prepare('SELECT * FROM fishes WHERE available = 1 AND stock > 0 ORDER BY created_at DESC');
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_fish_by_id($id) {
    global $mysqli;
    $stmt = $mysqli->prepare('SELECT * FROM fishes WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function cart_items() {
    return $_SESSION['cart'] ?? [];
}

function cart_count() {
    $items = cart_items();
    $count = 0;
    foreach ($items as $item) {
        $count += ($item['quantity_pair'] ?? 0) + ($item['quantity_single'] ?? 0);
    }
    return $count;
}

function cart_total() {
    $items = cart_items();
    $total = 0;
    foreach ($items as $item) {
        $total += ($item['quantity_pair'] * $item['price_pair']) + ($item['quantity_single'] * $item['price_single']);
    }
    return $total;
}

function format_price($value) {
    return '₹' . number_format($value, 2);
}

function order_status_label($status) {
    $map = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
    ];
    return $map[$status] ?? ucfirst($status);
}

function parse_image_list($csv) {
    if (empty($csv)) {
        return [];
    }
    return array_filter(explode(',', $csv));
}

function upload_files($fieldName, $allowedTypes, $destinationDir) {
    $uploaded = [];
    if (!isset($_FILES[$fieldName])) {
        return $uploaded;
    }
    foreach ($_FILES[$fieldName]['name'] as $index => $name) {
        if (empty($name) || $_FILES[$fieldName]['error'][$index] !== UPLOAD_ERR_OK) {
            continue;
        }
        $tmpName = $_FILES[$fieldName]['tmp_name'][$index];
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedTypes, true)) {
            continue;
        }
        $filename = time() . '_' . uniqid() . '.' . $extension;
        if (move_uploaded_file($tmpName, $destinationDir . $filename)) {
            $uploaded[] = $filename;
        }
    }
    return $uploaded;
}
