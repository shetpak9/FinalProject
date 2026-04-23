<?php
require 'bootstrap.php';

require 'mailer.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->execute([$username, $email, $password]);

$userId = $pdo->lastInsertId();

// Check if MFA is enabled system-wide
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE mfa_enabled = 1");
$mfaEnabledCount = $stmt->fetchColumn();

if ($mfaEnabledCount > 0) {
    $pdo->prepare("UPDATE users SET mfa_enabled = 1 WHERE id = ?")->execute([$userId]);
}

$token = bin2hex(random_bytes(32));
$expires = date("Y-m-d H:i:s", strtotime("+1 day"));

$pdo->prepare("INSERT INTO email_verifications (user_id, token, expires_at) VAlUES (?, ?, ?)")->execute([$userId, $token, $expires]);

$link = "http://localhost/FinalProject/controllers/verify-email.php?token=$token";

sendMail($email, "Verify your email", "Click: $link");

echo "Check your email to verify account.";
sleep(3);
header("Location: view/login.view.php");
