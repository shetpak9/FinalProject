<?php
session_start();
require 'controllers/bootstrap.php';

ensureTableExist($pdo);
$pdo->query("
    UPDATE event 
    SET event_status = 'Ongoing'
    WHERE event_status = 'Upcoming'
    AND time <= NOW()
");
$pdo->query("
    DELETE FROM event
    WHERE time <= NOW() - INTERVAL 12 HOUR
");

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/* =========================
   PUBLIC ROUTES
========================= */

$publicRoutes = [
    '/FinalProject/login',
    '/FinalProject/register',
    '/FinalProject/verify-email',
    '/FinalProject/forgot',
    '/FinalProject/mfa',
    '/FinalProject/backup',
    '/FinalProject/reset'
];

/* =========================
   REDIRECT ROOT
========================= */

if ($path === '/FinalProject/') {

    if (!isset($_SESSION['user_id'])) {
        header("Location: /FinalProject/login");
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

    header("Location: /FinalProject/login");
    exit;
}

/* =========================
   ROUTES
========================= */

$route = [
    '/FinalProject/login' => 'view/login.view.php',
    '/FinalProject/register' => 'view/register.view.php',
    '/FinalProject/forgot' => 'view/forgot.view.php',
    '/FinalProject/mfa' => 'view/mfa.view.php',
    '/FinalProject/backup' => 'view/backup.view.php',
    '/FinalProject/reset' => 'view/reset.view.php',
    '/FinalProject/addlocation' => 'controllers/addlocation.php',
    '/FinalProject/locationmanagement' => 'controllers/locationmanagement.php',
    '/FinalProject/viewlogs' => 'controllers/view-logs.php',
    '/FinalProject/interactivemap' => 'controllers/interactivemap.php',
    '/FinalProject/setup-mfa' => 'controllers/setup-mfa.php',
    '/FinalProject/mapcontrol'=> 'controllers/mapcontrol.php'
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