<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$route = [
    '/FinalProject/' => 'controllers/dashboard.php',
    '/FinalProject/addlocation' => 'controllers/addlocation.php',
    '/FinalProject/locationmanagement' => 'controllers/locationmanagement.php',
    '/FinalProject/viewlogs' => 'controllers/viewlogs.php',
];

if (str_contains($path, '/src/api/location')) {
    require 'src/api/location.php';
} elseif (array_key_exists($path, $route)) {
    require $route[$path];
} else {
    http_response_code(404);
    echo "Page not found.";
}