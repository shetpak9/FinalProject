<?php

spl_autoload_register(fn ($class) => require __DIR__ . '/../config/' . $class . '.php');

header("Content-type: application/json; charset=UTF-8");

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", trim($path, "/"));

$resource = $parts[3] ?? null;
$id = $parts[4] ?? null;

if ($resource !== "favorite") {
    http_response_code(404);
    exit;
}

$database = new Database("localhost", "school_map", "root", "");

$favgateway = new FavGateway($database);

$controller = new FavController($favgateway);

$controller->processRequest($_SERVER['REQUEST_METHOD']);