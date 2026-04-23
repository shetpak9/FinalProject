<?php

session_start();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/* =========================
   PUBLIC ROUTES
========================= */

$publicRoutes = [
    '/FinalProject/login',
    '/FinalProject/register',
    '/FinalProject/verify-email',
];

/* =========================
   REDIRECT ROOT
========================= */

if ($path === '/FinalProject/') {

    if (!isset($_SESSION['user_id'])) {
        header("Location: /FinalProject/view/login.view");
        exit;
    }

    // role-based landing
    if (($_SESSION['role'] ?? '') === 'admin') {
        require 'controllers/dashboard.php';
    } else {
        require 'controllers/interactivemap.php';
    }

    exit;
}

/* =========================
   GLOBAL AUTH GUARD
========================= */

// If NOT logged in AND route is not public → redirect login
if (!isset($_SESSION['user_id']) && !in_array($path, $publicRoutes)) {

    header("Location: /FinalProject/view/login.view");
    exit;
}

/* =========================
   ROUTES
========================= */

$route = [
    '/FinalProject/addlocation' => 'controllers/addlocation.php',
    '/FinalProject/locationmanagement' => 'controllers/locationmanagement.php',
    '/FinalProject/viewlogs' => 'controllers/viewlogs.php',
    '/FinalProject/interactivemap' => 'controllers/interactivemap.php',
    '/FinalProject/setup-mfa' => 'controllers/setup-mfa.php',
];

/* =========================
   API ROUTES (protect if needed)
========================= */

if (str_starts_with($path, '/src/api/location')) {

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        exit;
    }
    require 'src/api/location.php';
    exit;
}
if (str_starts_with($path, '/src/api/favorite')) {

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        exit;
    }

    require 'src/api/favorite.php';
    exit;
}

/* =========================
   NORMAL ROUTES
========================= */

if (array_key_exists($path, $route)) {
    require $route[$path];
    exit;
}

/* =========================
   404
========================= */

http_response_code(404);
echo "Page not found.";