<?php
require __DIR__ . '/includes/bootstrap.php';

if (isset($_SESSION['admin_id'])) {
    redirect('admin/index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        redirect('admin/index.php');
    }

    $error = 'Invalid login credentials.';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - JudiciaryPRO</title>
    <link rel="stylesheet" href="../assets/libs/bootstrap/css/bootstrap.min.css">
    <style>
        body { background: #f5f7fb; }
        .login-card { max-width: 420px; margin: 80px auto; background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #e6e8ef; }
    </style>
</head>
<body>
<div class="login-card">
    <h3>Admin Login</h3>
    <p class="text-muted">Sign in to manage JudiciaryPRO.</p>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= h($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div>
</body>
</html>
