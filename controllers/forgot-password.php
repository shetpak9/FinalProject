<?php
require 'bootstrap.php';
require 'mailer.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$_POST['email']]);
$user = $stmt->fetch();

if (!$user) {
    die("email not found");
}
$token = bin2hex(random_bytes(32));
$expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

$pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)")->execute([$user['id'], $token, $expires]);

$link = "http://localhost/FinalProject/reset?token=$token";

sendMail($user['email'], "Reset Password", "Click: $link");

header("Location: /FinalProject/login?error=reset");
