<?php
require 'bootstrap.php';

$userId = $_SESSION['user_id'];

for ($i = 0; $i < 5; $i++) {
    $code = bin2hex(random_bytes(4));

    $pdo->prepare("INSERT INTO backup_codes (user_id, code_hash) VALUES (?, ?)")
        ->execute([$userId, password_hash($code, PASSWORD_DEFAULT)]);

    echo "Backup code: $code <br>";
}

