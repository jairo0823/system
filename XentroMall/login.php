<?php
session_start();
require 'config.php';

// Redirect logged-in users to their dashboards
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_dashboard.php');
        exit;
    } elseif ($_SESSION['role'] === 'tenant') {
        header('Location: tenant_dashboard.php');
        exit;
    }
}

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $input = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($input) || empty($password)) {
        $login_error = 'Please fill all required fields.';
    } else {
        // Check admins table
        $stmt = $pdo->prepare('SELECT id, username, password, "admin" as role FROM admins WHERE username = :input1 OR email = :input2');
        $stmt->execute(['input1' => $input, 'input2' => $input]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE (username = :input1 OR email = :input2) AND role = :role');
            $stmt->execute(['input1' => $input, 'input2' => $input, 'role' => 'tenant']);
            $tenant = $stmt->fetch();

            if ($tenant && password_verify($password, $tenant['password'])) {
                $_SESSION['user_id'] = $tenant['id'];
                $_SESSION['username'] = $tenant['username'];
                $_SESSION['role'] = 'tenant';
                header('Location: tenant_dashboard.php');
                exit;
            } else {
                $login_error = 'Invalid username/email or password.';
            }
        }
    }
}
?>

<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   Login - TMS
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      min-height: 100vh;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #0f172a;
    }
    /* Background image container */
    #background-image {
      position: fixed;
      inset: 0;
      z-index: -10;
      overflow: hidden;
    }
    #background-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.5) saturate(1.2);
    }
    /* Overlay blur */
    #background-blur {
      position: fixed;
      inset: 0;
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      z-index: -5;
    }
  </style>
 </head>
 <body>
  <div id="background-image">
   <img alt="Background image showing a blue and green gradient with abstract smooth waves and light reflections" height="1080" src="img/logo.jpg" width="1920"/>
  </div>
  <div id="background-blur">
  </div>
  <div class="max-w-md w-full bg-white bg-opacity-20 backdrop-blur-md rounded-3xl shadow-2xl overflow-hidden mx-4 my-12">
   <div class="p-10 flex flex-col justify-center">
    <div class="mb-8 text-center">
     <h1 class="text-5xl font-extrabold text-white tracking-wide">
      TMS
     </h1>
     <p class="mt-2 text-teal-200 font-medium">
      Tenant Management System
     </p>
    </div>
    <form action="login.php" class="space-y-6" method="post">
     <div>
      <label class="block text-teal-100 font-semibold mb-2" for="username">
       Username or Email
      </label>
      <input class="w-full rounded-lg bg-white bg-opacity-20 border border-teal-300 placeholder-teal-200 text-white focus:outline-none focus:ring-4 focus:ring-teal-400 focus:border-teal-400 px-4 py-3 transition" id="username" name="username" placeholder="Enter your username or email" required="" type="text"/>
     </div>
     <div>
      <label class="block text-teal-100 font-semibold mb-2" for="password">
       Password
      </label>
      <input class="w-full rounded-lg bg-white bg-opacity-20 border border-teal-300 placeholder-teal-200 text-white focus:outline-none focus:ring-4 focus:ring-teal-400 focus:border-teal-400 px-4 py-3 transition" id="password" name="password" placeholder="Enter your password" required="" type="password"/>
     </div>
     <button class="w-full bg-gradient-to-r from-blue-500 to-green-400 hover:from-green-400 hover:to-blue-500 text-white font-semibold rounded-lg py-3 shadow-lg transition" name="login_submit" type="submit">
      Log In
     </button>
     <div class="flex justify-between text-sm text-teal-200">
      <a class="hover:underline hover:text-white" href="forgot_password.php">
       Forgot password?
      </a>
      <span>
       Don't have an account?
       <a class="font-semibold hover:underline hover:text-white" href="tenant_register.php">
        Register here
       </a>
      </span>
     </div>
    </form>
   </div>
  </div>
 </body>
</html>
