<?php
session_start();
require 'bootstrap.php';

if (!isset($_SESSION['auth_pending'])) {
    die("Session expired");
}

$userId = $_SESSION['auth_pending']['id'];
$inputCode = $_POST['code'];

$stmt = $pdo->prepare("SELECT * FROM backup_codes WHERE user_id=? AND used=0");
$stmt->execute([$userId]);

foreach ($stmt->fetchAll() as $row) {

    if (password_verify($inputCode, $row['code_hash'])) {

        $pdo->prepare("UPDATE backup_codes SET used=1 WHERE id=?")
            ->execute([$row['id']]);

        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $_SESSION['auth_pending']['role'];
        unset($_SESSION['auth_pending']);

        echo "Logged in with backup code";
        exit;
    }
}

echo "Invalid backup code";