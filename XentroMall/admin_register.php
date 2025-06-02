<?php
session_start();
require __DIR__ . '/config.php';

$register_error = '';
$register_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $register_error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = 'Invalid email format.';
    } elseif ($password !== $confirm_password) {
        $register_error = 'Passwords do not match.';
    } else {
        // Check if username or email already exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->fetch()) {
            $register_error = 'Username or email already exists.';
        } else {
            // Insert new admin user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role, created_at) VALUES (:username, :email, :password, :role, NOW())');
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password,
                'role' => 'admin'
            ]);
            $register_success = 'Admin registered successfully. You can now <a href="login.php">login</a>.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .register-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .register-form {
      background: #fff;
      padding: 2rem 2.5rem;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(13, 202, 240, 0.15);
      max-width: 420px;
      width: 100%;
    }
    .form-control:focus {
      border-color: #0dcaf0;
      box-shadow: 0 0 8px rgba(13, 202, 240, 0.5);
    }
    .btn-info {
      background-color: #0dcaf0;
      border-color: #0dcaf0;
      transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .btn-info:hover {
      background-color: #0a8ac9;
      border-color: #0a8ac9;
    }
    .text-center a {
      color: #0dcaf0;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    .text-center a:hover {
      color: #0a8ac9;
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container register-container">
  <div class="register-form">

    <h2 class="mb-4 text-center">Admin Registration</h2>

    <?php if (!empty($register_error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($register_error); ?></div>
    <?php endif; ?>

    <?php if (!empty($register_success)): ?>
      <div class="alert alert-success"><?php echo $register_success; ?></div>
    <?php endif; ?>

    <form method="post" action="admin_register.php" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required />
      </div>

      <div class="mb-4">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required />
      </div>

      <button type="submit" name="register_submit" class="btn btn-info w-100">Register</button>

      <div class="text-center mt-3">
        <a href="login.php">Back to Login</a>
      </div>
    </form>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
