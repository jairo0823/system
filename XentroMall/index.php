<?php
session_start();
$login_error = '';
if (isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<html class="scroll-smooth" lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   Xentro Mall Portal
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
            font-family: 'Montserrat', sans-serif;
        }
        /* Navbar background overlay animation */
        .navbar-bg {
            background: rgba(0, 64, 64, 0.75);
            backdrop-filter: saturate(180%) blur(10px);
            transition: background-color 0.5s ease;
        }
        .navbar-bg:hover {
            background: rgba(0, 96, 128, 0.85);
        }
        /* Button pulse animation */
        @keyframes pulse-green {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(34,197,94, 0.7);
            }
            50% {
                box-shadow: 0 0 10px 10px rgba(34,197,94, 0);
            }
        }
        .btn-pulse-green {
            animation: pulse-green 2.5s infinite;
        }
        @keyframes pulse-blue {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(59,130,246, 0.7);
            }
            50% {
                box-shadow: 0 0 10px 10px rgba(59,130,246, 0);
            }
        }
        .btn-pulse-blue {
            animation: pulse-blue 2.5s infinite;
        }
        /* Hero fade-in animation */
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s forwards;
            animation-delay: 0.3s;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* About section slide-in animation */
        .slide-in-left {
            opacity: 0;
            transform: translateX(-30px);
            animation: slideInLeft 1s forwards;
            animation-delay: 0.6s;
        }
        @keyframes slideInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
  </style>
 </head>
 <body class="min-h-screen bg-fixed text-green-900 relative">
  <!-- Background Image -->
  <img alt="img/bg.jpg" class="fixed inset-0 w-full h-full object-cover -z-10" height="100%" src="img/bg.jpg"/>
  <!-- Background Overlay -->   
  <!-- Navbar -->
  <nav class="navbar-bg fixed w-full z-30 shadow-lg">
   <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
     <a class="text-white font-bold text-2xl tracking-wide hover:text-blue-300 transition-colors duration-300" href="#">
      Xentro Mall
     </a>
     <div class="hidden md:flex space-x-8">
      <a class="text-white font-semibold hover:text-blue-300 transition-colors duration-300" href="#">
       Home
      </a>
      <a class="text-white font-semibold hover:text-blue-300 transition-colors duration-300" href="#about-system-section">
       About
      </a>
      <a class="text-white font-semibold hover:text-blue-300 transition-colors duration-300" href="contact.php">
       Contacts
      </a>
     </div>
     <div class="hidden md:flex space-x-4">
      <a class="btn-pulse-green bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md transition duration-300" href="tenant_register.php">
       Register
      </a>
      <a class="btn-pulse-blue bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md transition duration-300" href="login.php">
       Login
      </a>
     </div>
     <!-- Mobile menu button -->
     <div class="md:hidden">
      <button aria-label="Toggle menu" class="text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400" id="mobile-menu-button">
       <i class="fas fa-bars fa-lg">
       </i>
      </button>
     </div>
    </div>
   </div>
   <!-- Mobile menu -->
   <div class="hidden md:hidden bg-green-800 bg-opacity-90" id="mobile-menu">
    <a class="block px-4 py-3 text-white font-semibold border-b border-green-700 hover:bg-green-700 transition" href="#">
     Home
    </a>
    <a class="block px-4 py-3 text-white font-semibold border-b border-green-700 hover:bg-green-700 transition" href="#about-system-section">
     About
    </a>
    <a class="block px-4 py-3 text-white font-semibold border-b border-green-700 hover:bg-green-700 transition" href="contact.php">
     Contacts
    </a>
    <div class="flex flex-col space-y-2 p-4">
     <a class="btn-pulse-green bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md text-center transition duration-300" href="tenant_register.php">
      Register
     </a>
     <a class="btn-pulse-blue bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md text-center transition duration-300" href="login.php">
      Login
     </a>
    </div>
   </div>
  </nav>
  <!-- Hero Section -->
  <section class="fade-in-up max-w-3xl mx-auto mt-36 bg-white bg-opacity-90 rounded-3xl shadow-2xl px-8 py-16 text-center">
   <h1 class="text-4xl md:text-5xl font-extrabold mb-6 text-green-900 drop-shadow-md">
    Welcome to Xentro Mall Portal
   </h1>
   <p class="text-lg md:text-xl text-green-800 mb-10 leading-relaxed">
    Your gateway to convenient mall services and tenant management.
   </p>
   <div class="flex justify-center gap-6 flex-wrap">
    <a class="btn-pulse-green bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-full shadow-lg transition duration-300 inline-block" href="tenant_register.php">
     Register
    </a>
    <a class="btn-pulse-blue bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-full shadow-lg transition duration-300 inline-block" href="login.php">
     Login
    </a>
   </div>
  </section>
  <!-- About Section -->
  <section class="slide-in-left max-w-3xl mx-auto mt-20 bg-gradient-to-r from-green-100 to-blue-100 rounded-3xl shadow-lg p-10 text-green-900" id="about-system-section">
   <h2 class="text-3xl font-bold mb-6 border-b-4 border-green-500 inline-block pb-2">
    About the System
   </h2>
   <p class="mb-5 text-lg leading-relaxed">
    Xentro Mall Portal is a comprehensive system designed to streamline mall services and tenant management. It provides tenants with an easy way to register, submit documents, and manage their profiles. The system also facilitates maintenance requests, renewal applications, and payment tracking, ensuring efficient mall operations.
   </p>
   <p class="text-lg leading-relaxed">
    The portal is built with security and user experience in mind, featuring secure login, role-based access, and responsive design for accessibility across devices.
   </p>
  </section>
  <script>
   const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
  </script>
 </body>
</html>
