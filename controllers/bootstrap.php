<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../src/config/Database.php';

$database = new Database("localhost", "school_map", "root", "");
$pdo = $database->getconnection();