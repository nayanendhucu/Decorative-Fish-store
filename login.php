<?php
require_once __DIR__ . '/../includes/functions.php';

if (is_customer_logged_in()) {
    header('Location: ../index.php');
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$phone || !$password) {
        $message = 'Enter phone and password.';
    } else {
        global $mysqli;
        $stmt = $mysqli->prepare('SELECT id, password_hash FROM customers WHERE phone = ? LIMIT 1');
        $stmt->bind_param('s', $phone);
        $stmt->execute();
        $customer = $stmt->get_result()->fetch_assoc();
        if ($customer && password_verify($password, $customer['password_hash'])) {
            $_SESSION['customer_id'] = $customer['id'];
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        $message = 'Invalid login credentials.';
    }
}

$page_title = 'Customer Login';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="container auth-page">
    <h1>Customer Login</h1>
    <?php if ($message): ?><div class="alert alert-warning"><?= sanitize($message) ?></div><?php endif; ?>
    <form method="post" class="auth-form">
        <label>Phone</label>
        <input type="tel" name="phone" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="btn btn-primary" type="submit">Login</button>
        <p class="auth-footer">Don't have an account? <a href="register.php">Register here</a>.</p>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php';
