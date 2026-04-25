<?php
session_start();
require 'bootstrap.php';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
$stmt->execute([$_POST['username']]);
$user = $stmt->fetch();


if (!$user || !password_verify($_POST['password'], $user['password'])) {
    $error = true;
    header("Location: /FinalProject/login?error=invalid");
    exit;
}

if (!$user['email_verified']) {
    header("Location: /FinalProject/login?error=verify");
    exit;
}

/* =========================
   STORE PENDING USER (MFA FLOW)
========================= */
$_SESSION['auth_pending'] = [
    'id' => $user['id'],
    'role' => $user['role']
];

/* =========================
   MFA CHECK
========================= */
if ($user['mfa_enabled']) {
    if (!empty($user['mfa_secret'])) {
        header("Location: /FinalProject/mfa");
    } else {
        header("Location: ../controllers/setup-mfa");
    }
    exit;
}

/* =========================
   NO MFA → DIRECT LOGIN
========================= */
$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];

unset($_SESSION['auth_pending']);

header("Location: /FinalProject/");
exit;