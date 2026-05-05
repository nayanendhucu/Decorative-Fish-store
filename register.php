<?php
require_once __DIR__ . '/../includes/functions.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$name || !$phone || !$email || !$password || !$confirm) {
        $message = 'Please fill in all fields.';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
    } else {
        global $mysqli;
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO customers (name, phone, email, password_hash, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->bind_param('ssss', $name, $phone, $email, $passwordHash);
        if ($stmt->execute()) {
            $_SESSION['customer_id'] = $stmt->insert_id;
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        $message = 'Unable to create account. Please try again.';
    }
}

$page_title = 'Customer Register';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="container auth-page">
    <h1>Create Account</h1>
    <?php if ($message): ?><div class="alert alert-warning"><?= sanitize($message) ?></div><?php endif; ?>
    <form method="post" class="auth-form">
        <label>Name</label>
        <input type="text" name="name" required>
        <label>Phone</label>
        <input type="tel" name="phone" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>
        <button class="btn btn-primary" type="submit">Register</button>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php';
