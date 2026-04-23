<?php

spl_autoload_register(fn ($class) => require __DIR__ . '/../config/' . $class . '.php');

header("Content-type: application/json; charset=UTF-8");

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", trim($path, "/"));

$resource = $parts[3] ?? null;
$id = $parts[4] ?? null;

if ($resource !== "lost_item") {
    http_response_code(404);
    exit;
}

$database = new Database("localhost", "school_map", "root", "");

$itemgateway = new LostItemGateway($database);

$controller = new LostItemController($itemgateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);