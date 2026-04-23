<?php
require 'bootstrap.php';

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT * FROM email_verifications WHERE token=?");
$stmt->execute([$token]);
$data = $stmt->fetch();

if (!$data) die("Invalid token");

if (strtotime($data['expires_at']) < time()) {
    die("Token expired");
}

$pdo->prepare("UPDATE users SET email_verified=1 WHERE id=?")
    ->execute([$data['user_id']]);

$pdo->prepare("DELETE FROM email_verifications WHERE id=?")
    ->execute([$data['id']]);

echo "Email verified!";
