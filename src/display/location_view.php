<?php
spl_autoload_register(fn ($class) => require __DIR__ . '/../config/' . $class . '.php');

$database = new Database("localhost", "school_map", "root", "");
$gateway = new LocationGateway($database);